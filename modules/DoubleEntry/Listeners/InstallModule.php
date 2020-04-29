<?php

namespace Modules\DoubleEntry\Listeners;

use App\Events\Module\Installed as Event;
use App\Models\Banking\Account;
use App\Models\Banking\Transaction;
use App\Models\Banking\Transfer;
use App\Models\Purchase\Bill;
use App\Models\Sale\Invoice;
use App\Models\Setting\Tax;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Modules\DoubleEntry\Models\Account as Coa;
use Modules\DoubleEntry\Models\AccountBank;
use Modules\DoubleEntry\Models\AccountTax;
use Modules\DoubleEntry\Models\Journal;
use Modules\DoubleEntry\Models\Ledger;

class InstallModule
{
    /**
     * Handle the event.
     *
     * @param  Event $event
     * @return void
     */
    public function handle(Event $event)
    {
        if ($event->alias != 'double-entry') {
            return;
        }

        $this->callSeeds();

        $this->copyData();

        $this->copyFiles();
    }

    protected function callSeeds()
    {
        Artisan::call('company:seed', [
            'company' => session('company_id'),
            '--class' => 'Modules\DoubleEntry\Database\Seeds\Install',
        ]);
    }

    protected function copyData()
    {
        $this->copyAccounts();
        $this->copyTransfers();
        $this->copyTaxes();
        $this->copyInvoices();
        $this->copyIncomeTransactions();
        $this->copyBills();
        $this->copyExpenseTransactions();
    }

    protected function copyAccounts()
    {
        Account::cursor()->each(function ($bank) {
            $chart = Coa::firstOrCreate([
                'company_id' => session('company_id'),
                'type_id' => setting('double-entry.types_bank', 6),
                'code' => Coa::max('code') + 1,
                'name' => $bank->name,
                'enabled' => 1,
            ]);

            AccountBank::firstOrCreate([
                'company_id' => session('company_id'),
                'account_id' => $chart->id,
                'bank_id' => $bank->id,
            ]);
        });
    }

    protected function copyTransfers()
    {
        Transfer::cursor()->each(function ($transfer) {
            $expense_transaction = $transfer->expense_transaction;
            $income_transaction = $transfer->income_transaction;

            $expense_transaction_account_id = AccountBank::where('bank_id', $expense_transaction->account_id)->pluck('account_id')->first();
            $income_transaction_account_id = AccountBank::where('bank_id', $income_transaction->account_id)->pluck('account_id')->first();

            if (empty($expense_transaction_account_id) || empty($income_transaction_account_id)) {
                return;
            }

            $journal = Journal::firstOrCreate([
                'company_id' => $transfer->company_id,
                'amount' => $expense_transaction->amount,
                'paid_at' => $expense_transaction->paid_at,
                'description' => $expense_transaction->description ?: '...',
                'reference' => 'transfer:' . $transfer->id,
            ]);

            $l1 = $journal->ledger()->firstOrCreate([
                'company_id' => $transfer->company_id,
                'account_id' => $expense_transaction_account_id,
                'issued_at' => $journal->paid_at,
                'entry_type' => 'item',
                'credit' => $journal->amount,
            ]);
            $expense_transaction->reference = 'journal-entry-ledger:' . $l1->id;
            $expense_transaction->save();

            $l2 = $journal->ledger()->firstOrCreate([
                'company_id' => $transfer->company_id,
                'account_id' => $income_transaction_account_id,
                'issued_at' => $journal->paid_at,
                'entry_type' => 'item',
                'debit' => $journal->amount,
            ]);
            $income_transaction->reference = 'journal-entry-ledger:' . $l2->id;
            $income_transaction->save();
        });
    }

    protected function copyTaxes()
    {
        Tax::cursor()->each(function ($tax) {
            $chart = Coa::firstOrCreate([
                'company_id' => session('company_id'),
                'type_id' => setting('double-entry.types_tax', 17),
                'code' => Coa::max('code') + 1,
                'name' => $tax->name,
                'enabled' => 1,
            ]);

            AccountTax::firstOrCreate([
                'company_id' => session('company_id'),
                'account_id' => $chart->id,
                'tax_id' => $tax->id,
            ]);
        });
    }

    protected function copyInvoices()
    {
        Invoice::with(['items', 'item_taxes', 'transactions'])->cursor()->each(function ($invoice) {
            $accounts_receivable_id = Coa::code(setting('double-entry.accounts_receivable', 120))->pluck('id')->first();

            $ledger = Ledger::firstOrCreate([
                'company_id' => session('company_id'),
                'account_id' => $accounts_receivable_id,
                'ledgerable_id' => $invoice->id,
                'ledgerable_type' => get_class($invoice),
                'issued_at' => $invoice->invoiced_at,
                'entry_type' => 'total',
                'debit' => $invoice->amount,
            ]);

            $invoice->items()->each(function ($item) use($invoice) {
                $account_id = Coa::code(setting('double-entry.accounts_sales', 400))->pluck('id')->first();

                $ledger = Ledger::firstOrCreate([
                    'company_id' => session('company_id'),
                    'account_id' => $account_id,
                    'ledgerable_id' => $item->id,
                    'ledgerable_type' => get_class($item),
                    'issued_at' => $invoice->invoiced_at,
                    'entry_type' => 'item',
                    'credit' => $item->total,
                ]);
            });

            $invoice->item_taxes()->each(function ($item_tax) use($invoice) {
                $account_id = AccountTax::where('tax_id', $item_tax->tax_id)->pluck('account_id')->first();

                $ledger = Ledger::firstOrCreate([
                    'company_id' => session('company_id'),
                    'account_id' => $account_id,
                    'ledgerable_id' => $item_tax->id,
                    'ledgerable_type' => get_class($item_tax),
                    'issued_at' => $invoice->invoiced_at,
                    'entry_type' => 'item',
                    'credit' => $item_tax->amount,
                ]);
            });

            $invoice->transactions()->each(function ($transaction) use($accounts_receivable_id) {
                $account_id = AccountBank::where('bank_id', $transaction->account_id)->pluck('account_id')->first();

                $ledger = Ledger::firstOrCreate([
                    'company_id' => session('company_id'),
                    'account_id' => $account_id,
                    'ledgerable_id' => $transaction->id,
                    'ledgerable_type' => get_class($transaction),
                    'issued_at' => $transaction->paid_at,
                    'entry_type' => 'total',
                    'debit' => $transaction->amount,
                ]);

                $ledger = Ledger::firstOrCreate([
                    'company_id' => session('company_id'),
                    'account_id' => $accounts_receivable_id,
                    'ledgerable_id' => $transaction->id,
                    'ledgerable_type' => get_class($transaction),
                    'issued_at' => $transaction->paid_at,
                    'entry_type' => 'item',
                    'credit' => $transaction->amount,
                ]);
            });

        });
    }

    protected function copyIncomeTransactions()
    {
        Transaction::type('income')->isNotDocument()->isNotTransfer()->cursor()->each(function ($transaction) {
            $account_id = AccountBank::where('bank_id', $transaction->account_id)->pluck('account_id')->first();

            $ledger = Ledger::firstOrCreate([
                'company_id' => session('company_id'),
                'account_id' => $account_id,
                'ledgerable_id' => $transaction->id,
                'ledgerable_type' => get_class($transaction),
                'issued_at' => $transaction->paid_at,
                'entry_type' => 'total',
                'debit' => $transaction->amount,
            ]);

            $accounts_receivable_id = Coa::code(setting('double-entry.accounts_receivable', 120))->pluck('id')->first();

            $ledger = Ledger::firstOrCreate([
                'company_id' => session('company_id'),
                'account_id' => $accounts_receivable_id,
                'ledgerable_id' => $transaction->id,
                'ledgerable_type' => get_class($transaction),
                'issued_at' => $transaction->paid_at,
                'entry_type' => 'item',
                'credit' => $transaction->amount,
            ]);
        });
    }

    protected function copyBills()
    {
        Bill::with(['items', 'item_taxes', 'transactions'])->cursor()->each(function ($bill) {
            $accounts_payable_id = Coa::code(setting('double-entry.accounts_payable', 200))->pluck('id')->first();

            $ledger = Ledger::firstOrCreate([
                'company_id' => session('company_id'),
                'account_id' => $accounts_payable_id,
                'ledgerable_id' => $bill->id,
                'ledgerable_type' => get_class($bill),
                'issued_at' => $bill->billed_at,
                'entry_type' => 'total',
                'credit' => $bill->amount,
            ]);

            $bill->items()->each(function ($item) use($bill) {
                $account_id = Coa::code(setting('double-entry.accounts_expenses', 628))->pluck('id')->first();

                $ledger = Ledger::firstOrCreate([
                    'company_id' => session('company_id'),
                    'account_id' => $account_id,
                    'ledgerable_id' => $item->id,
                    'ledgerable_type' => get_class($item),
                    'issued_at' => $bill->billed_at,
                    'entry_type' => 'item',
                    'debit' => $item->total,
                ]);
            });

            $bill->item_taxes()->each(function ($item_tax) use($bill) {
                $account_id = AccountTax::where('tax_id', $item_tax->tax_id)->pluck('account_id')->first();

                $ledger = Ledger::firstOrCreate([
                    'company_id' => session('company_id'),
                    'account_id' => $account_id,
                    'ledgerable_id' => $item_tax->id,
                    'ledgerable_type' => get_class($item_tax),
                    'issued_at' => $bill->billed_at,
                    'entry_type' => 'item',
                    'debit' => $item_tax->amount,
                ]);
            });

            $bill->transactions()->each(function ($transaction) use($accounts_payable_id) {
                $account_id = AccountBank::where('bank_id', $transaction->account_id)->pluck('account_id')->first();

                $ledger = Ledger::firstOrCreate([
                    'company_id' => session('company_id'),
                    'account_id' => $account_id,
                    'ledgerable_id' => $transaction->id,
                    'ledgerable_type' => get_class($transaction),
                    'issued_at' => $transaction->paid_at,
                    'entry_type' => 'total',
                    'credit' => $transaction->amount,
                ]);

                $ledger = Ledger::firstOrCreate([
                    'company_id' => session('company_id'),
                    'account_id' => $accounts_payable_id,
                    'ledgerable_id' => $transaction->id,
                    'ledgerable_type' => get_class($transaction),
                    'issued_at' => $transaction->paid_at,
                    'entry_type' => 'item',
                    'debit' => $transaction->amount,
                ]);
            });
        });
    }

    protected function copyExpenseTransactions()
    {
        Transaction::type('expense')->isNotDocument()->isNotTransfer()->cursor()->each(function ($transaction) {
            $account_id = AccountBank::where('bank_id', $transaction->account_id)->pluck('account_id')->first();

            $ledger = Ledger::firstOrCreate([
                'company_id' => session('company_id'),
                'account_id' => $account_id,
                'ledgerable_id' => $transaction->id,
                'ledgerable_type' => get_class($transaction),
                'issued_at' => $transaction->paid_at,
                'entry_type' => 'total',
                'credit' => $transaction->amount,
            ]);

            $accounts_payable_id = Coa::code(setting('double-entry.accounts_payable', 200))->pluck('id')->first();

            $ledger = Ledger::firstOrCreate([
                'company_id' => session('company_id'),
                'account_id' => $accounts_payable_id,
                'ledgerable_id' => $transaction->id,
                'ledgerable_type' => get_class($transaction),
                'issued_at' => $transaction->paid_at,
                'entry_type' => 'item',
                'debit' => $transaction->amount,
            ]);
        });
    }

    protected function copyFiles()
    {
        $files = [
            'modules/DoubleEntry/Resources/assets/chart-of-accounts.xlsx' => 'public/files/import/chart-of-accounts.xlsx',
        ];

        foreach ($files as $source => $target) {
            File::copy(base_path($source), base_path($target));
        }
    }
}

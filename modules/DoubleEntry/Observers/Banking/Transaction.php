<?php

namespace Modules\DoubleEntry\Observers\Banking;

use App\Abstracts\Observer;
use App\Models\Banking\Transaction as Model;
use App\Models\Setting\Category;
use Illuminate\Support\Str;
use Modules\DoubleEntry\Models\Account as Coa;
use Modules\DoubleEntry\Models\AccountBank;
use Modules\DoubleEntry\Models\Ledger;

class Transaction extends Observer
{
    //
    // Events
    //
    public function created(Model $transaction)
    {
        if ($this->hasDocument($transaction)) {
            $this->updateDocumentLedger($transaction, false);
        }

        $this->createTransactionLedger($transaction);
    }

    public function updated(Model $transaction)
    {
        if ($this->hasDocument($transaction)) {
            $this->updateDocumentLedger($transaction, false);
        }

        $this->updateTransactionLedger($transaction);
    }

    public function deleted(Model $transaction)
    {
        if ($this->hasDocument($transaction)) {
            $this->updateDocumentLedger($transaction, true);
        }

        $this->deleteTransactionLedger($transaction);
    }

    //
    // Invoice/Bill
    //
    public function updateDocumentLedger($transaction, $is_delete)
    {
        $doc_type = ($transaction->type == 'income') ? 'invoice' : 'bill';
        $document = $transaction->$doc_type;

        $ledger = Ledger::record($document->id, get_class($document))->first();

        if (empty($ledger)) {
            return;
        }

        $type = $this->getDocumentType($transaction);

        if ($is_delete) {
            $ledger->update([
                'company_id' => $ledger->company_id,
                'account_id' => $ledger->account_id,
                'ledgerable_id' => $document->id,
                'ledgerable_type' => get_class($document),
                'issued_at' => $document->{$type['date_field']},
                'entry_type' => 'total',
                $type['amount_field'] => $ledger->{$type['amount_field']} + $transaction->amount,
            ]);

            return;
        }

        $remainder = $document->amount - $transaction->amount;
        if ($remainder == 0) {
            $ledger->delete();
        } else {
            $ledger->update([
                'company_id' => $ledger->company_id,
                'account_id' => $ledger->account_id,
                'ledgerable_id' => $document->id,
                'ledgerable_type' => get_class($document),
                'issued_at' => $document->{$type['date_field']},
                'entry_type' => 'total',
                $type['amount_field'] => $remainder,
            ]);
        }
    }

    //
    // Revenue/Payment
    //
    public function createTransactionLedger($transaction)
    {
        if ($this->isJournal($transaction) || $this->isTransfer($transaction)) {
            return;
        }

        $account_id = AccountBank::where('bank_id', $transaction->account_id)->pluck('account_id')->first();

        if (empty($account_id)) {
            return;
        }

        $type = $this->getTransactionType($transaction);

        Ledger::create([
            'company_id' => $transaction->company_id,
            'account_id' => $account_id,
            'ledgerable_id' => $transaction->id,
            'ledgerable_type' => get_class($transaction),
            'issued_at' => $transaction->paid_at,
            'entry_type' => 'total',
            $type['total_field'] => $transaction->amount,
        ]);

        Ledger::create([
            'company_id' => $transaction->company_id,
            'account_id' =>  $type['account_id'],
            'ledgerable_id' => $transaction->id,
            'ledgerable_type' => get_class($transaction),
            'issued_at' => $transaction->paid_at,
            'entry_type' => 'item',
            $type['item_field'] => $transaction->amount,
        ]);
    }

    public function updateTransactionLedger($transaction)
    {
        if ($this->isJournal($transaction) || $this->isTransfer($transaction)) {
            return;
        }

        $ledger = Ledger::record($transaction->id, get_class($transaction))->where('entry_type', 'total')->first();

        if (empty($ledger)) {
            return;
        }

        $account_id = AccountBank::where('bank_id', $transaction->account_id)->pluck('account_id')->first();

        if (empty($account_id)) {
            return;
        }

        $type = $this->getTransactionType($transaction);

        $ledger->update([
            'company_id' => $transaction->company_id,
            'account_id' => $account_id,
            'ledgerable_id' => $transaction->id,
            'ledgerable_type' => get_class($transaction),
            'issued_at' => $transaction->paid_at,
            'entry_type' => 'total',
            $type['total_field']  => $transaction->amount,
        ]);

        $ledger = Ledger::record($transaction->id, get_class($transaction))->where('entry_type', 'item')->first();

        if (empty($ledger)) {
            return;
        }

        $ledger->update([
            'company_id' => $transaction->company_id,
            'account_id' => $type['account_id'],
            'ledgerable_id' => $transaction->id,
            'ledgerable_type' => get_class($transaction),
            'issued_at' => $transaction->paid_at,
            'entry_type' => 'item',
            $type['item_field']  => $transaction->amount,
        ]);
    }

    public function deleteTransactionLedger($transaction)
    {
        if ($this->isJournal($transaction) || $this->isTransfer($transaction)) {
            return;
        }

        Ledger::record($transaction->id, get_class($transaction))->delete();
    }

    //
    // Helpers
    //
    public function getTransactionType($transaction)
    {
        if ($transaction->type == 'income') {
            return [
                'total_field'   => 'debit',
                'item_field'    => 'credit',
                'account_id'    => Coa::code(setting('double-entry.accounts_sales', 400))->pluck('id')->first()
            ];
        } else {
            $type = [
                'total_field'   => 'credit',
                'item_field'    => 'debit',
                'account_id'    => Coa::code(setting('double-entry.accounts_expenses', 628))->pluck('id')->first()
            ];
        }

        return $type;
    }

    public function getDocumentType($transaction)
    {
        if ($transaction->type == 'income') {
            $type = [
                'amount_field'  => 'debit',
                'date_field'    => 'invoiced_at',
                //'account_id'    => Coa::code(setting('double-entry.accounts_receivable', 120))->pluck('id')->first(),
            ];
        } else {
            $type = [
                'amount_field'  => 'credit',
                'date_field'    => 'billed_at',
                //'account_id'    => Coa::code(setting('double-entry.accounts_payable', 200))->pluck('id')->first(),
            ];
        }

        return $type;
    }

    protected function isJournal($transaction)
    {
        if (empty($transaction->reference)) {
            return false;
        }

        if (!Str::contains($transaction->reference, 'journal-entry-ledger:')) {
            return false;
        }

        return true;
    }

    protected function isTransfer($transaction)
    {
        //$transfer_id = (int) Category::transfer();
        $transfer_id = (int) Category::disableCache()->where('type', 'other')->pluck('id')->first();

        if ($transaction->category_id != $transfer_id) {
            return false;
        }

        return true;
    }

    protected function hasDocument($transaction)
    {
        return !empty($transaction->document_id);
    }
}

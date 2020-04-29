<?php

namespace Modules\DoubleEntry\Observers\DoubleEntry;

use App\Abstracts\Observer;
use App\Models\Banking\Transfer;
use App\Models\Banking\Transaction;
use App\Models\Setting\Category;
use Illuminate\Support\Str;
use Modules\DoubleEntry\Models\AccountBank;
use Modules\DoubleEntry\Models\Ledger as Model;

class JournalLedger extends Observer
{
    /**
     * Listen to the created event.
     *
     * @param  Model  $ledger
     * @return void
     */
    public function created(Model $ledger)
    {
        if (!$this->isJournal($ledger)) {
            return;
        }

        list($class, $field, $type) = $this->getConstants($ledger);

        if (!$bank = $this->getBank($ledger)) {
            return;
        }

        $journal = $ledger->ledgerable;

        if ($this->isTransfer($journal)) {
            return;
        }

        $request = [
            'company_id' => $journal->company_id,
            'type' => $type,
            'account_id' => $bank->bank_id,
            'paid_at' => $journal->paid_at,
            'currency_code' => setting('default.currency'),
            'currency_rate' => '1',
            'description' => $journal->description,
            'payment_method' => setting('default.payment_method'),
            'amount' => $ledger->$field,
            'category_id' => Category::where('type', $type)->enabled()->pluck('id')->first(),
            'reference' => 'journal-entry-ledger:' . $ledger->id,
        ];

        $record = $class::create($request);
    }
    /**
     * Listen to the created event.
     *
     * @param  Model  $ledger
     * @return void
     */
    public function updated(Model $ledger)
    {
        if (!$this->isJournal($ledger)) {
            return;
        }

        list($class, $field, $type) = $this->getConstants($ledger);

        if (!$record = $this->getRecord($ledger, $class)) {
            return;
        }

        $bank = $this->getBank($ledger);

        if (!empty($bank)) {
            $journal = $ledger->ledgerable;

            if ($this->isTransfer($journal)) {
                return;
            }

            $record->account_id = $bank->bank_id;
            $record->paid_at = $journal->paid_at;
            $record->amount = $ledger->$field;
            $record->description = $journal->description;
            $record->reference = 'journal-entry-ledger:' . $ledger->id;

            $record->save();
        } else {
            $record->delete();
        }
    }

    /**
     * Listen to the deleted event.
     *
     * @param  Model  $ledger
     * @return void
     */
    public function deleted(Model $ledger)
    {
        if (!$this->isJournal($ledger)) {
            return;
        }

        Transaction::type('income')->where('document_id', '=', '')->where('reference', 'journal-entry-ledger:' . $ledger->id)->delete();
        Transaction::type('expense')->where('document_id', '=', '')->where('reference', 'journal-entry-ledger:' . $ledger->id)->delete();

        $journal = $ledger->ledgerable;

        if ($this->isTransfer($journal)) {
            $transfer_id = str_replace('transfer:', '', $journal->reference);
            Transfer::where('id', $transfer_id)->delete();
        }
    }

    protected function isJournal($ledger)
    {
        if ($ledger->ledgerable_type == 'Modules\DoubleEntry\Models\Journal') {
            return true;
        }

        return false;
    }

    protected function isTransfer($journal)
    {
        if (!Str::contains($journal->reference, 'transfer:')) {
            return false;
        }

        return true;
    }

    protected function getConstants($ledger)
    {
        if (!empty($ledger->credit)) {
            $class = '\App\Models\Banking\Transaction';
            $field = 'credit';
            $type = 'expense';
        } else {
            $class = '\App\Models\Banking\Transaction';
            $field = 'debit';
            $type = 'income';
        }

        return [$class, $field, $type];
    }

    protected function getRecord($ledger, $class)
    {
        return $class::where('reference', 'journal-entry-ledger:' . $ledger->id)->first();
    }

    protected function getBank($ledger)
    {
        return AccountBank::where('account_id', $ledger->account_id)->first();
    }
}

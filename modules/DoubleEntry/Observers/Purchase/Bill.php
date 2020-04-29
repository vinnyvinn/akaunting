<?php

namespace Modules\DoubleEntry\Observers\Purchase;

use App\Abstracts\Observer;
use App\Models\Purchase\Bill as Model;
use Modules\DoubleEntry\Models\Account as Coa;
use Modules\DoubleEntry\Models\Ledger;

class Bill extends Observer
{
    /**
     * Listen to the created event.
     *
     * @param  Model  $bill
     * @return void
     */
    public function created(Model $bill)
    {
        Ledger::create([
            'company_id' => $bill->company_id,
            'account_id' => Coa::code(setting('double-entry.accounts_payable', 200))->pluck('id')->first(),
            'ledgerable_id' => $bill->id,
            'ledgerable_type' => get_class($bill),
            'issued_at' => $bill->billed_at,
            'entry_type' => 'total',
            'credit' => $bill->amount,
        ]);
    }

    /**
     * Listen to the created event.
     *
     * @param  Model  $bill
     * @return void
     */
    public function updated(Model $bill)
    {
        $ledger = Ledger::record($bill->id, get_class($bill))->first();

        if (empty($ledger)) {
            return;
        }

        $amount = $bill->amount;

        if ($bill->transactions->count()) {
            $paid = 0;

            foreach ($bill->transactions as $transaction) {
                $paid += $transaction->amount;
            }

            $amount = $amount - $paid;
        }

        $ledger->update([
            'company_id' => $bill->company_id,
            'account_id' => Coa::code(setting('double-entry.accounts_payable', 200))->pluck('id')->first(),
            'ledgerable_id' => $bill->id,
            'ledgerable_type' => get_class($bill),
            'issued_at' => $bill->billed_at,
            'entry_type' => 'total',
            'credit' => $amount,
        ]);
    }

    /**
     * Listen to the deleted event.
     *
     * @param  Model  $bill
     * @return void
     */
    public function deleted(Model $bill)
    {
        Ledger::record($bill->id, get_class($bill))->delete();
    }
}

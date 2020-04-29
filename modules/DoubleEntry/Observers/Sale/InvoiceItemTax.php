<?php

namespace Modules\DoubleEntry\Observers\Sale;

use App\Abstracts\Observer;
use App\Models\Sale\InvoiceItemTax as Model;
use Modules\DoubleEntry\Models\AccountTax;
use Modules\DoubleEntry\Models\Ledger;

class InvoiceItemTax extends Observer
{
    /**
     * Listen to the created event.
     *
     * @param  Model  $item_tax
     * @return void
     */
    public function created(Model $item_tax)
    {
        $account_id = AccountTax::where('tax_id', $item_tax->tax_id)->pluck('account_id')->first();

        $ledger = Ledger::create([
            'company_id' => $item_tax->company_id,
            'account_id' => $account_id,
            'ledgerable_id' => $item_tax->id,
            'ledgerable_type' => get_class($item_tax),
            'issued_at' => $item_tax->invoice->invoiced_at,
            'entry_type' => 'item',
            'credit' => $item_tax->amount,
        ]);
    }

    /**
     * Listen to the deleted event.
     *
     * @param  Model  $item_tax
     * @return void
     */
    public function deleted(Model $item_tax)
    {
        Ledger::record($item_tax->id, get_class($item_tax))->delete();
    }
}

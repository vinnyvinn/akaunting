<?php

namespace Modules\DoubleEntry\Observers\Sale;

use App\Abstracts\Observer;
use App\Models\Sale\InvoiceItem as Model;
use Modules\DoubleEntry\Models\Account as Coa;
use Modules\DoubleEntry\Models\Ledger;

class InvoiceItem extends Observer
{
    /**
     * Listen to the created event.
     *
     * @param  Model  $item
     * @return void
     */
    public function created(Model $item)
    {
        $account_id = null;

        $r_items = request()->input('items');
        if (is_array($r_items)) {
            foreach ($r_items as $r_item) {
                if ($r_item['name'] != $item->name) {
                    continue;
                }

                $account_id = $r_item['de_account_id'];

                break;
            }
        }

        if (empty($account_id)) {
            $account_id = Coa::code(setting('double-entry.accounts_sales', 400))->pluck('id')->first();
        }

        $ledger = Ledger::create([
            'company_id' => $item->company_id,
            'account_id' => $account_id,
            'ledgerable_id' => $item->id,
            'ledgerable_type' => get_class($item),
            'issued_at' => $item->invoice->invoiced_at,
            'entry_type' => 'item',
            'credit' => $item->total,
        ]);
    }

    /**
     * Listen to the deleted event.
     *
     * @param  Model  $item
     * @return void
     */
    public function deleted(Model $item)
    {
        Ledger::record($item->id, get_class($item))->delete();
    }
}

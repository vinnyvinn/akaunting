<?php

namespace Modules\Inventory\Observers\Purchase;

use App\Abstracts\Observer;
use App\Models\Auth\User;
use App\Models\Purchase\BillItem as BillItemModel;
use Modules\Inventory\Models\History as Model;
use Modules\Inventory\Models\Item;

class BillItem extends Observer
{
    /**
     * Listen to the created event.
     *
     * @param  BillItemModel $bill_item
     *
     * @return void
     */
    public function created(BillItemModel $bill_item)
    {
        $item = $bill_item->item;

        if (empty($item)) {
            return false;
        }

        //Item Stock
        $inventory_item = Item::where('item_id', $bill_item->item_id)->first();

        $inventory_item->opening_stock -= (float) $bill_item->quantity;
        $inventory_item->save();

        Model::where('type_type', get_class($bill_item))
            ->where('type_id', $bill_item->id)
            ->delete();

        $warehouse_id = setting('inventory.default_warehouse');

        Model::create([
            'company_id' => $bill_item->company_id,
            'user_id' => user() ? user()->id : User::first()->id,
            'item_id' => $item->id,
            'type_id' => $bill_item->id,
            'type_type' => get_class($bill_item),
            'warehouse_id' => $warehouse_id,
            'quantity' => $bill_item->quantity,
        ]);
    }

    /**
     * Listen to the deleted event.
     *
     * @param  Model $item
     *
     * @return void
     */
    public function deleted(BillItemModel $bill_item)
    {
        $item = $bill_item->item;

        $item_stock = Item::where('item_id', $item->item_id)->first();

        $item_stock->opening_stock += (float)  $bill_item->quantity;
        $item_stock->save();

        Model::where('type_type', get_class($bill_item))
            ->where('type_id', $bill_item->id)
            ->delete();
    }
}

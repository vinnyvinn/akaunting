<?php

namespace Modules\Inventory\Observers\Common;

use App\Abstracts\Observer;
use App\Models\Common\Item as ItemModel;
use Modules\Inventory\Models\Item as InventoryItem;

class Item extends Observer
{
    /**
     * Listen to the created event.
     *
     * @param  ItemModel $item
     *
     * @return void
     */
    public function created(ItemModel $item)
    {
        if (empty($item)) {
            return false;
        }

        $inventory_item = InventoryItem::where('item_id', $item->id)->first();

        if (empty($inventory_item)) {
            $inventory_item =  InventoryItem::create([
                'company_id'            => session('company_id'),
                'item_id'               => $item->id,
                'sku'                   => isset($item->sku) ? $item->sku : '',
                'opening_stock'         => isset($item->quantity) ? $item->quantity : 0,
                'opening_stock_value'   => $item->purchase_price,
            ]);
        } else {
            $inventory_item->sku = isset($item->sku) ? $item->sku : '';
            $inventory_item->opening_stock = isset($item->quantity) ? $item->quantity : 0;
            $inventory_item->update();
        }
    }
}

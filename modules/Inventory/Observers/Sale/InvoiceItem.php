<?php

namespace Modules\Inventory\Observers\Sale;

use App\Abstracts\Observer;
use App\Models\Auth\User;
use App\Models\Common\Item as CommonItem;
use App\Models\Sale\InvoiceItem as InvoiceItemModel;
use Modules\Inventory\Models\Item as InventoryItem;
use Modules\Inventory\Models\History as Model;
use Modules\Inventory\Models\Item;

class InvoiceItem extends Observer
{
    /**
     * Listen to the created event.
     *
     * @param  InvoiceItemModel $invoice_item
     *
     * @return void
     */
    public function created(InvoiceItemModel $invoice_item)
    {
        $item = CommonItem::where('id', $invoice_item->item_id)->first();

        if (empty($item)) {
            return;
        }

        //Item Stock
        $inventory_item = Item::where('item_id', $invoice_item->item_id)->first();

        if (empty($inventory_item)) {
            $inventory_item = InventoryItem::create([
                'company_id'            => session('company_id'),
                'item_id'               => $item->id,
                'sku'                   => isset($item->sku) ? $item->sku : '',
                'opening_stock'         => isset($item->quantity) ? $item->quantity : 0,
                'opening_stock_value'   => $item->purchase_price,
            ]);
        }

        $inventory_item->opening_stock -= (float) $invoice_item->quantity;
        $inventory_item->save();

        Model::where('type_type', get_class($invoice_item))
            ->where('type_id', $invoice_item->id)
            ->delete();

        $warehouse_id = setting('inventory.default_warehouse');

        Model::create([
            'company_id' => $invoice_item->company_id,
            'user_id' => user() ? user()->id : User::first()->id,
            'item_id' => $item->id,
            'type_id' => $invoice_item->id,
            'type_type' => get_class($invoice_item),
            'warehouse_id' => $warehouse_id,
            'quantity' => $invoice_item->quantity,
        ]);
    }

    /**
     * Listen to the deleted event.
     *
     * @param  Model $item
     *
     * @return void
     */
    public function deleted(InvoiceItemModel $invoice_item)
    {
        $item = $invoice_item->item;

        $item_stock = Item::where('item_id', $item->item_id)->first();

        $item_stock->opening_stock += (float)  $invoice_item->quantity;
        $item_stock->save();

        Model::where('type_type', get_class($invoice_item))
            ->where('type_id', $invoice_item->id)
            ->delete();
    }
}

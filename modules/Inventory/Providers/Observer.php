<?php

namespace Modules\Inventory\Providers;

use App\Models\Common\Item;
use App\Models\Purchase\Bill;
use App\Models\Sale\InvoiceItem;
use App\Models\Purchase\BillItem;
use Illuminate\Support\ServiceProvider;

class Observer extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function boot()
    {
        // Invoices
        InvoiceItem::observe('Modules\Inventory\Observers\Sale\InvoiceItem');

        // Bills
        BillItem::observe('Modules\Inventory\Observers\Purchase\BillItem');

        // Item
        Item::observe('Modules\Inventory\Observers\Common\Item');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}

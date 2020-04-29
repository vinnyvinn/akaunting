<?php

namespace Modules\DoubleEntry\Providers;

use Illuminate\Support\ServiceProvider;
use View;

class ViewComposer extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function boot()
    {
        // Invoice
        View::composer(['sales.invoices.create', 'sales.invoices.edit'], 'Modules\DoubleEntry\Http\ViewComposers\InvoiceTable');
        View::composer(['sales.invoices.create', 'sales.invoices.edit'], 'Modules\DoubleEntry\Http\ViewComposers\InvoiceInput');
        View::composer(['sales.invoices.item'], 'Modules\DoubleEntry\Http\ViewComposers\InvoiceInput');

        // Bill
        View::composer(['purchases.bills.create', 'purchases.bills.edit'], 'Modules\DoubleEntry\Http\ViewComposers\BillTable');
        View::composer(['purchases.bills.create', 'purchases.bills.edit'], 'Modules\DoubleEntry\Http\ViewComposers\BillInput');
        View::composer(['purchases.bills.item'], 'Modules\DoubleEntry\Http\ViewComposers\BillInput');
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

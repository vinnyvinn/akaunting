<?php

namespace Modules\CustomFields\Providers;

use App\Models\Sale\Invoice;
use App\Events\Sale\InvoicePrinting;
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
        // Companies
        View::composer(['common.companies.create', 'common.companies.edit'], 'Modules\CustomFields\Http\ViewComposers\Companies');

        // Items
        View::composer(['common.items.create', 'common.items.edit'], 'Modules\CustomFields\Http\ViewComposers\Items');

        $invoice = app(Invoice::class);

        $invoice->template_path = 'sales.invoices.print_default';

        event(new InvoicePrinting($invoice)); // print test edilecek

        // Invoices
        View::composer(['sales.invoices.create', 'sales.invoices.edit', 'sales.invoices.item'], 'Modules\CustomFields\Http\ViewComposers\Invoices');

        View::composer(['sales.invoices.item'], 'Modules\CustomFields\Http\ViewComposers\InvoiceItems');

        View::composer(['sales.invoices.show', $invoice->template_path], 'Modules\CustomFields\Http\ViewComposers\InvoiceShow');

        View::composer([$invoice->template_path], 'Modules\CustomFields\Http\ViewComposers\InvoicePrint');

        // Revenues
        View::composer(['sales.revenues.create', 'sales.revenues.edit'], 'Modules\CustomFields\Http\ViewComposers\Revenues');

        // Customers
        View::composer(['sales.customers.create', 'sales.customers.edit'], 'Modules\CustomFields\Http\ViewComposers\Customers');

        // Bills
        View::composer(['purchases.bills.create', 'purchases.bills.edit', 'purchases.bills.item'], 'Modules\CustomFields\Http\ViewComposers\Bills');
        View::composer(['purchases.bills.item'], 'Modules\CustomFields\Http\ViewComposers\BillItems');

        View::composer(['purchases.bills.show'], 'Modules\CustomFields\Http\ViewComposers\BillShow');

        View::composer(['purchases.bills.bill'], 'Modules\CustomFields\Http\ViewComposers\BillPrint');

        // Payments
        View::composer(['purchases.payments.create', 'purchases.payments.edit'], 'Modules\CustomFields\Http\ViewComposers\Payments');

        // Vendors
        View::composer(['purchases.vendors.create', 'purchases.vendors.edit'], 'Modules\CustomFields\Http\ViewComposers\Vendors');

        // Accounts
        View::composer(['banking.accounts.create', 'banking.accounts.edit'], 'Modules\CustomFields\Http\ViewComposers\Accounts');

        // Transfers
        View::composer(['banking.transfers.create', 'banking.transfers.edit'], 'Modules\CustomFields\Http\ViewComposers\Transfers');
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

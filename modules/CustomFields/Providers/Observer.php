<?php

namespace Modules\CustomFields\Providers;

use App\Models\Common\Company;
use App\Models\Common\Item;
use App\Models\Sale\Invoice;
use App\Models\Sale\InvoiceItem;
use App\Models\Purchase\Bill;
use App\Models\Banking\Account;
use App\Models\Banking\Transaction;
use App\Models\Banking\Transfer;
use App\Models\Common\Contact;
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
        // Company
        Company::observe('Modules\CustomFields\Observers\Common\Company');

        // Items
        Item::observe('Modules\CustomFields\Observers\Common\Item');

        //Contact -> Customer and Vendor
        Contact::observe('Modules\CustomFields\Observers\Common\Contact');

        //Sale
        Invoice::observe('Modules\CustomFields\Observers\Sale\Invoice');
        InvoiceItem::observe('Modules\CustomFields\Observers\Sale\InvoiceItem');

        // Purchase
        Bill::observe('Modules\CustomFields\Observers\Purchase\Bill');
        BillItem::observe('Modules\CustomFields\Observers\Purchase\BillItem');

        // Banking
        Account::observe('Modules\CustomFields\Observers\Banking\Account');
        Transfer::observe('Modules\CustomFields\Observers\Banking\Transfer');
        Transaction::observe('Modules\CustomFields\Observers\Banking\Transaction');
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

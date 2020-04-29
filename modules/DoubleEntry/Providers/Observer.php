<?php

namespace Modules\DoubleEntry\Providers;

use App\Models\Banking\Account;
use App\Models\Banking\Transaction;
use App\Models\Banking\Transfer;
use App\Models\Purchase\Bill;
use App\Models\Purchase\BillItem;
use App\Models\Purchase\BillItemTax;
use App\Models\Sale\Invoice;
use App\Models\Sale\InvoiceItem;
use App\Models\Sale\InvoiceItemTax;
use App\Models\Setting\Tax;
use Illuminate\Support\ServiceProvider;
use Modules\DoubleEntry\Models\Ledger;

class Observer extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function boot()
    {
        Account::observe('Modules\DoubleEntry\Observers\Banking\Account');
        Bill::observe('Modules\DoubleEntry\Observers\Purchase\Bill');
        BillItem::observe('Modules\DoubleEntry\Observers\Purchase\BillItem');
        BillItemTax::observe('Modules\DoubleEntry\Observers\Purchase\BillItemTax');
        Invoice::observe('Modules\DoubleEntry\Observers\Sale\Invoice');
        InvoiceItem::observe('Modules\DoubleEntry\Observers\Sale\InvoiceItem');
        InvoiceItemTax::observe('Modules\DoubleEntry\Observers\Sale\InvoiceItemTax');
        Ledger::observe('Modules\DoubleEntry\Observers\DoubleEntry\JournalLedger');
        Tax::observe('Modules\DoubleEntry\Observers\Setting\Tax');
        Transaction::observe('Modules\DoubleEntry\Observers\Banking\Transaction');
        Transfer::observe('Modules\DoubleEntry\Observers\Banking\Transfer');
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

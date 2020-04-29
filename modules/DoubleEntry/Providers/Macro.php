<?php

namespace Modules\DoubleEntry\Providers;

use App\Models\Banking\Transaction;
use App\Models\Purchase\Bill;
use App\Models\Purchase\BillItem;
use App\Models\Purchase\BillItemTax;
use App\Models\Sale\Invoice;
use App\Models\Sale\InvoiceItem;
use App\Models\Sale\InvoiceItemTax;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\ServiceProvider;

class Macro extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        Builder::macro('de_ledger', function() {
            $model = $this->getModel();

            if ($model instanceof Bill) {
                return $model->hasOne('Modules\DoubleEntry\Models\Ledger', 'ledgerable_id', 'id')->where('ledgerable_type', 'App\Models\Purchase\Bill');
            }

            if ($model instanceof BillItem) {
                return $model->hasOne('Modules\DoubleEntry\Models\Ledger', 'ledgerable_id', 'id')->where('ledgerable_type', 'App\Models\Purchase\BillItem');
            }

            if ($model instanceof BillItemTax) {
                return $model->hasOne('Modules\DoubleEntry\Models\Ledger', 'ledgerable_id', 'id')->where('ledgerable_type', 'App\Models\Purchase\BillItemTax');
            }

            if ($model instanceof Invoice) {
                return $model->hasOne('Modules\DoubleEntry\Models\Ledger', 'ledgerable_id', 'id')->where('ledgerable_type', 'App\Models\Sale\Invoice');
            }

            if ($model instanceof InvoiceItem) {
               return $model->hasOne('Modules\DoubleEntry\Models\Ledger', 'ledgerable_id', 'id')->where('ledgerable_type', 'App\Models\Sale\InvoiceItem');
            }

            if ($model instanceof InvoiceItemTax) {
               return $model->hasOne('Modules\DoubleEntry\Models\Ledger', 'ledgerable_id', 'id')->where('ledgerable_type', 'App\Models\Sale\InvoiceItemTax');
            }

            if ($model instanceof Transaction) {
                return $model->hasOne('Modules\DoubleEntry\Models\Ledger', 'ledgerable_id', 'id')->where('ledgerable_type', 'App\Models\Banking\Transaction');
            }

            unset(static::$macros['de_ledger']);

            return $model->de_ledger();
        });
    }

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}

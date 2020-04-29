<?php

namespace Modules\Projects\Providers;

use App\Models\Banking\Transaction;
use App\Models\Purchase\Bill;
use App\Models\Sale\Invoice;
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
        Builder::macro('project', function() {
            $model = $this->getModel();

            if ($model instanceof Bill) {
                return $model->hasOne('Modules\Projects\Models\ProjectBill', 'bill_id', 'id');
            }

            if ($model instanceof Invoice) {
                return $model->hasOne('Modules\Projects\Models\ProjectInvoice', 'invoice_id', 'id');
            }

            if ($model instanceof Transaction) {
                if ($model->type == 'income') {
                    return $model->hasOne('Modules\Projects\Models\ProjectRevenue', 'revenue_id', 'id');
                } else {
                    return $model->hasOne('Modules\Projects\Models\ProjectPayment', 'payment_id', 'id');
                }
            }

            unset(static::$macros['project']);

            return $model->project();
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

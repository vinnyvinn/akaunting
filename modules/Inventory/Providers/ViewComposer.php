<?php

namespace Modules\Inventory\Providers;

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
        View::composer(['sales.invoices.create', 'sales.invoices.edit'], 'Modules\Inventory\Http\ViewComposers\Invoice');
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

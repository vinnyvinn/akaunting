<?php

namespace Modules\Crm\Providers;

use App\Models\Common\Contact;

use Illuminate\Support\ServiceProvider;

class Observer extends ServiceProvider
{
    public function boot()
    {
        //Contact::observe('Modules\Crm\Observers\Customer');
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

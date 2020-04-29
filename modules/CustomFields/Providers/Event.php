<?php

namespace Modules\CustomFields\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\CustomFields\Listeners\InstallModule;
use Modules\CustomFields\Listeners\ShowSetting;
use Modules\CustomFields\Listeners\Update\Version200;

class Event extends ServiceProvider
{
    /**
     * The event listener mappings for the module.
     *
     * @var array
     */
    protected $listen = [
        \App\Events\Module\Installed::class => [
            InstallModule::class,
        ],
        \App\Events\Module\SettingShowing::class => [
            ShowSetting::class,
        ],
        \App\Events\Install\UpdateFinished::class => [
            Version200::class,
        ],
    ];
}

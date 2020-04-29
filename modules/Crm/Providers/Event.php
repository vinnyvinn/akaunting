<?php

namespace Modules\Crm\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Crm\Listeners\AddMenu;
use Modules\Crm\Listeners\InstallModule;
use Modules\Crm\Listeners\ShowSetting;
use Modules\Crm\Listeners\Update\Version200;
use Modules\Crm\Listeners\Update\Version201;

class Event extends ServiceProvider
{
    /**
     * The event listener mappings for the module.
     *
     * @var array
     */
    protected $listen = [
        \App\Events\Menu\AdminCreated::class => [
            AddMenu::class,
        ],
        \App\Events\Module\Installed::class => [
            InstallModule::class,
        ],
        \App\Events\Module\SettingShowing::class => [
            ShowSetting::class,
        ],
        \App\Events\Install\UpdateFinished::class => [
            Version200::class,
            Version201::class,
        ],
    ];
}

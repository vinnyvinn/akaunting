<?php

namespace Modules\Inventory\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Inventory\Listeners\AdminMenu;
use Modules\Inventory\Listeners\InstallModule;
use Modules\Inventory\Listeners\ShowSetting;
use Modules\Inventory\Listeners\Update\Version200;
use Modules\Inventory\Listeners\Update\Version206;

class Event extends ServiceProvider
{
    /**
     * The event listener mappings for the module.
     *
     * @var array
     */
    protected $listen = [
        \App\Events\Menu\AdminCreated::class => [
            AdminMenu::class,
        ],
        \App\Events\Module\Installed::class => [
            InstallModule::class,
        ],
        \App\Events\Module\SettingShowing::class => [
            ShowSetting::class,
        ],
        \App\Events\Install\UpdateFinished::class => [
            Version200::class,
        ],
        \App\Events\Install\UpdateFinished::class => [
            Version206::class,
        ],
    ];


        /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [
        'Modules\Inventory\Listeners\AddInventoryToReports',
    ];

}


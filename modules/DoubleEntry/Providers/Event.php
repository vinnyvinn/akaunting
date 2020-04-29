<?php

namespace Modules\DoubleEntry\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\DoubleEntry\Listeners\AddMenu;
use Modules\DoubleEntry\Listeners\InstallModule;
use Modules\DoubleEntry\Listeners\ShowSetting;
use Modules\DoubleEntry\Listeners\Update\Version200;

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
        ],
    ];

    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [
        'Modules\DoubleEntry\Listeners\AddCoaToCoreReports',
        'Modules\DoubleEntry\Listeners\AddCoaToGeneralLedger',
    ];
}

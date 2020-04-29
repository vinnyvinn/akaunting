<?php
namespace Modules\Projects\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class Event extends ServiceProvider
{

    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\Menu\AdminCreated' => [
            'Modules\Projects\Listeners\AddAdminMenu',
        ],
        'App\Events\Module\Installed' => [
            'Modules\Projects\Listeners\InstallModule',
        ],
        'App\Events\Install\UpdateFinished' => [
            'Modules\Projects\Listeners\Update\Version200',
        ]
    ];

    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [
        'Modules\Projects\Listeners\AddProjectsToReports',
    ];
}

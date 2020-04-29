<?php
namespace Modules\Projects\Providers;

use App\Models\Purchase\Bill;
use App\Models\Banking\Transaction;
use App\Models\Sale\Invoice;
use Modules\Projects\Models\Comment;
use Modules\Projects\Models\Discussion;
use Modules\Projects\Models\Project;
use Illuminate\Support\ServiceProvider;
use Modules\Projects\Models\SubTask;
use Modules\Projects\Models\Task;

class Observer extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        Invoice::observe('Modules\Projects\Observers\Invoice');
        Transaction::observe('Modules\Projects\Observers\Transaction');
        Bill::observe('Modules\Projects\Observers\Bill');

        Project::observe('Modules\Projects\Observers\Project');
        Comment::observe('Modules\Projects\Observers\Comment');
        Discussion::observe('Modules\Projects\Observers\Discussion');
        Task::observe('Modules\Projects\Observers\Task');
        SubTask::observe('Modules\Projects\Observers\SubTask');
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

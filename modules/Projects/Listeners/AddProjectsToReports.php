<?php

namespace Modules\Projects\Listeners;

use App\Abstracts\Listeners\Report as Listener;
use App\Events\Report\FilterApplying;
use App\Events\Report\FilterShowing;
use App\Events\Report\GroupApplying;
use App\Events\Report\GroupShowing;
use App\Events\Report\RowsShowing;
use Modules\Projects\Models\Project;

class AddProjectsToReports extends Listener
{
    protected $classes = [
        'App\Reports\IncomeSummary',
        'App\Reports\ExpenseSummary',
        'App\Reports\IncomeExpenseSummary',
        'App\Reports\ProfitLoss',
    ];

    /**
     * Handle filter showing event.
     *
     * @param  $event
     * @return void
     */
    public function handleFilterShowing(FilterShowing $event)
    {
        if ($this->skipThisClass($event)) {
            return;
        }

        $event->class->filters['projects'] = Project::orderBy('name')->pluck('name', 'id')->toArray();
    }

    /**
     * Handle filter applying event.
     *
     * @param  $event
     * @return void
     */
    public function handleFilterApplying(FilterApplying $event)
    {
        if ($this->skipThisClass($event)) {
            return;
        }

        $projects = request('projects', []);

        if (empty($projects)) {
            return;
        }

        $event->model->whereHas('project', function ($query) use ($projects, $event) {
            $query->whereIn('project_id', (array) $projects);
        });
    }

    /**
     * Handle group showing event.
     *
     * @param  $event
     * @return void
     */
    public function handleGroupShowing(GroupShowing $event)
    {
        if ($this->skipThisClass($event)) {
            return;
        }

        $event->class->groups['project'] = trans_choice('projects::general.project', 1);
    }

    /**
     * Handle group applying event.
     *
     * @param  $event
     * @return void
     */
    public function handleGroupApplying(GroupApplying $event)
    {
        if ($this->skipThisClass($event)) {
            return;
        }

        $event->model->project_id = $event->model->project()->pluck('id')->first();
    }

    /**
     * Handle rows showing event.
     *
     * @param  $event
     * @return void
     */
    public function handleRowsShowing(RowsShowing $event)
    {
        if ($this->skipRowsShowing($event, 'project')) {
            return;
        }

        if ($projects = request('projects')) {
            $rows = collect($event->class->filters['projects'])->filter(function ($value, $key) use ($projects) {
                return in_array($key, $projects);
            });
        } else {
            $rows = $event->class->filters['projects'];
        }

        $this->setRowNamesAndValues($event, $rows);
    }
}

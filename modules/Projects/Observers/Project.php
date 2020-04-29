<?php

namespace Modules\Projects\Observers;

use App\Abstracts\Observer;
use Modules\Projects\Models\Activity;
use Modules\Projects\Models\Project as Model;

class Project extends Observer
{
    /**
     * Listen to the created event.
     *
     * @param Model $project
     * @return void
     */
    public function created(Model $project)
    {
        if (! $user = auth()->user()) {
            return;
        }

        Activity::create([
            'company_id' => session('company_id'),
            'project_id' => $project->id,
            'activity_id' => $project->id,
            'activity_type' => get_class($project),
            'description' => trans('projects::activities.created.project', [
                'user' => $user->name,
                'project' => $project->name
            ]),
            'created_by' => auth()->id()
        ]);
    }
}

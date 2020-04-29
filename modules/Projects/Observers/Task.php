<?php

namespace Modules\Projects\Observers;

use App\Abstracts\Observer;
use Modules\Projects\Models\Activity;
use Modules\Projects\Models\Task as Model;

class Task extends Observer
{
    /**
     * Listen to the created event.
     *
     * @param Model $task
     * @return void
     */
    public function created(Model $task)
    {
        Activity::create([
            'company_id' => session('company_id'),
            'project_id' => request('project_id'),
            'activity_id' => $task->id,
            'activity_type' => get_class($task),
            'description' => trans('projects::activities.created.task', [
                'user' => auth()->user()->name,
                'task' => $task->name
            ]),
            'created_by' => auth()->id()
        ]);
    }
}

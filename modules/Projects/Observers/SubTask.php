<?php

namespace Modules\Projects\Observers;

use App\Abstracts\Observer;
use Modules\Projects\Models\Activity;
use Modules\Projects\Models\SubTask as Model;

class SubTask extends Observer
{
    /**
     * Listen to the created event.
     *
     * @param Model $subtask
     * @return void
     */
    public function created(Model $subtask)
    {
        Activity::create([
            'company_id' => session('company_id'),
            'project_id' => request('project_id'),
            'activity_id' => $subtask->id,
            'activity_type' => get_class($subtask),
            'description' => trans('projects::activities.created.subtask', [
                'user' => auth()->user()->name,
                'task' => $subtask->task->name
            ]),
            'created_by' => auth()->id()
        ]);
    }
}

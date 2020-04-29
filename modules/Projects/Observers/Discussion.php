<?php

namespace Modules\Projects\Observers;

use App\Abstracts\Observer;
use Modules\Projects\Models\Activity;
use Modules\Projects\Models\Discussion as Model;

class Discussion extends Observer
{
    /**
     * Listen to the created event.
     *
     * @param Model $discussion
     * @return void
     */
    public function created(Model $discussion)
    {
        Activity::create([
            'company_id' => session('company_id'),
            'project_id' => request('project_id'),
            'activity_id' => $discussion->id,
            'activity_type' => get_class($discussion),
            'description' => trans('projects::activities.created.discussion', [
                'user' => auth()->user()->name,
                'discussion' => $discussion->name
            ]),
            'created_by' => auth()->id()
        ]);
    }
}

<?php

namespace Modules\Projects\Observers;

use App\Abstracts\Observer;
use Modules\Projects\Models\Activity;
use Modules\Projects\Models\Comment as Model;

class Comment extends Observer
{
    /**
     * Listen to the created event.
     *
     * @param Model $comment
     * @return void
     */
    public function created(Model $comment)
    {
        Activity::create([
            'company_id' => session('company_id'),
            'project_id' => request('project_id'),
            'activity_id' => $comment->id,
            'activity_type' => get_class($comment),
            'description' => trans('projects::activities.created.comment', [
                'user' => auth()->user()->name,
                'discussion' => $comment->discussion->name
            ]),
            'created_by' => auth()->id()
        ]);
    }
}

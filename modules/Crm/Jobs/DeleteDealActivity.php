<?php

namespace Modules\Crm\Jobs;

use App\Abstracts\Job;

class DeleteDealActivity extends Job
{
    protected $deal_activity;

    /**
     * Create a new job instance.
     *
     * @param  $deal_activity
     */
    public function __construct($deal_activity)
    {
        $this->deal_activity = $deal_activity;
    }

    /**
     * Execute the job.
     *
     * @return boolean|Exception
     */
    public function handle()
    {
        $this->authorize();

        $this->deal_activity->delete();

        return true;
    }

    /**
     * Determine if this action is applicable.
     *
     * @return void
     */
    public function authorize()
    {
        if ($relationships = $this->getRelationships()) {
            $message = trans('messages.warning.deleted', ['name' => $this->deal_activity->name, 'text' => implode(', ', $relationships)]);

            throw new \Exception($message);
        }
    }

    public function getRelationships()
    {
        $rels = [
        ];

        return $this->countRelationships($this->deal_activity, $rels);
    }
}

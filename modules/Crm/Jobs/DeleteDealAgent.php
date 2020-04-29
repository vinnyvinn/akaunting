<?php

namespace Modules\Crm\Jobs;

use App\Abstracts\Job;

class DeleteDealAgent extends Job
{
    protected $deal_agent;

    /**
     * Create a new job instance.
     *
     * @param  $deal_agent
     */
    public function __construct($deal_agent)
    {
        $this->deal_agent = $deal_agent;
    }

    /**
     * Execute the job.
     *
     * @return boolean|Exception
     */
    public function handle()
    {
        $this->authorize();

        $this->deal_agent->delete();

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
            $message = trans('messages.warning.deleted', ['name' => $this->deal_agent->name, 'text' => implode(', ', $relationships)]);

            throw new \Exception($message);
        }
    }

    public function getRelationships()
    {
        $rels = [
        ];

        return $this->countRelationships($this->deal_agent, $rels);
    }
}

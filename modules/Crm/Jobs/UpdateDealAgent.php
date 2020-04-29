<?php

namespace Modules\Crm\Jobs;

use App\Abstracts\Job;
use Modules\Crm\Models\DealAgent;

class UpdateDealAgent extends Job
{
    protected $deal_agent;

    protected $request;

    /**
     * Create a new job instance.
     *
     * @param  $deal
     * @param  $request
     */
    public function __construct($deal_agent, $request)
    {
        $this->deal_agent = $deal_agent;
        $this->request = $this->getRequestInstance($request);
    }

    /**
     * Execute the job.
     *
     * @return DealAgent
     */
    public function handle()
    {
        $this->authorize();

        $this->deal_agent->update($this->request->all());

        return $this->deal_agent;
    }

    /**
     * Determine if this action is applicable.
     *
     * @return void
     */
    public function authorize()
    {
        if (($this->request['enabled'] == 0) && ($relationships = $this->getRelationships())) {
            $message = trans('messages.warning.disabled', ['name' => $this->deal_agent->name, 'text' => implode(', ', $relationships)]);

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

<?php

namespace Modules\Crm\Jobs;

use App\Abstracts\Job;
use Modules\Crm\Models\DealCompetitor;

class UpdateDealCompetitor extends Job
{
    protected $deal_competitor;

    protected $request;

    /**
     * Create a new job instance.
     *
     * @param  $deal
     * @param  $request
     */
    public function __construct($deal_competitor, $request)
    {
        $this->deal_competitor = $deal_competitor;
        $this->request = $this->getRequestInstance($request);
    }

    /**
     * Execute the job.
     *
     * @return DealCompetitor
     */
    public function handle()
    {
        $this->authorize();

        $this->deal_competitor->update($this->request->all());

        return $this->deal_competitor;
    }

    /**
     * Determine if this action is applicable.
     *
     * @return void
     */
    public function authorize()
    {
        if (($this->request['enabled'] == 0) && ($relationships = $this->getRelationships())) {
            $message = trans('messages.warning.disabled', ['name' => $this->deal_competitor->name, 'text' => implode(', ', $relationships)]);

            throw new \Exception($message);
        }
    }

    public function getRelationships()
    {
        $rels = [
        ];

        return $this->countRelationships($this->deal_competitor, $rels);
    }
}

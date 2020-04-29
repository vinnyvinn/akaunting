<?php

namespace Modules\Crm\Jobs;

use App\Abstracts\Job;

class DeleteDealCompetitor extends Job
{
    protected $deal_competitor;

    /**
     * Create a new job instance.
     *
     * @param  $deal_competitor
     */
    public function __construct($deal_competitor)
    {
        $this->deal_competitor = $deal_competitor;
    }

    /**
     * Execute the job.
     *
     * @return boolean|Exception
     */
    public function handle()
    {
        $this->authorize();

        $this->deal_competitor->delete();

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
            $message = trans('messages.warning.deleted', ['name' => $this->deal_competitor->name, 'text' => implode(', ', $relationships)]);

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

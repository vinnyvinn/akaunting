<?php

namespace Modules\Crm\Jobs;

use App\Abstracts\Job;
use Modules\Crm\Models\Deal;

class UpdateDeal extends Job
{
    protected $deal;

    protected $request;

    /**
     * Create a new job instance.
     *
     * @param  $deal
     * @param  $request
     */
    public function __construct($deal, $request)
    {
        $this->deal = $deal;
        $this->request = $this->getRequestInstance($request);
    }

    /**
     * Execute the job.
     *
     * @return Deal
     */
    public function handle()
    {
        $this->authorize();

        $this->deal->update($this->request->all());

        return $this->deal;
    }

    /**
     * Determine if this action is applicable.
     *
     * @return void
     */
    public function authorize()
    {
        if (($this->request['enabled'] == 0) && ($relationships = $this->getRelationships())) {
            $message = trans('messages.warning.disabled', ['name' => $this->deal->name, 'text' => implode(', ', $relationships)]);

            throw new \Exception($message);
        }
    }

    public function getRelationships()
    {
        $rels = [
        ];

        return $this->countRelationships($this->deal, $rels);
    }
}

<?php

namespace Modules\Crm\Jobs;

use App\Abstracts\Job;
use Modules\Crm\Models\DealAgent;

class CreateDealAgent extends Job
{
    protected $request;

    /**
     * Create a new job instance.
     *
     * @param  $request
     */
    public function __construct($request)
    {
        $this->request = $this->getRequestInstance($request);
    }

    /**
     * Execute the job.
     *
     * @return Deal
     */
    public function handle()
    {
        foreach ((array) $this->request->get('user_id') as $user_id) {
            $deal_agent = DealAgent::create([
                'company_id' => $this->request->get('company_id'),
                'created_by' => $this->request->get('created_by'),
                'deal_id' => $this->request->get('deal_id'),
                'user_id' => $user_id,
            ]);
        }

        return $deal_agent;
    }
}

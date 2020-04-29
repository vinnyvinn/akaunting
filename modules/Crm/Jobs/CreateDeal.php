<?php

namespace Modules\Crm\Jobs;

use App\Abstracts\Job;
use Modules\Crm\Models\Deal;
use Modules\Crm\Models\DealPipeline;

class CreateDeal extends Job
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
        $stage_id = DealPipeline::find($this->request->get('pipeline_id'))->stages()->first()->id;

        $this->request['stage_id'] = $stage_id;

        $deal = Deal::create($this->request->all());

        return $deal;
    }
}

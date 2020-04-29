<?php

namespace Modules\Crm\Jobs;

use App\Abstracts\Job;
use Modules\Crm\Models\Log;

class UpdateLog extends Job
{
    protected $log;

    protected $request;

    /**
     * Create a new job instance.
     *
     * @param  $log
     * @param  $request
     */
    public function __construct($log, $request)
    {
        $this->log = $log;
        $this->request = $this->getRequestInstance($request);
    }

    /**
     * Execute the job.
     *
     * @return Log
     */
    public function handle()
    {
        $this->log->update($this->request->all());

        return $this->log;
    }
}

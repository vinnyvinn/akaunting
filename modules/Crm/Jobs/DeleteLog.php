<?php

namespace Modules\Crm\Jobs;

use App\Abstracts\Job;

class DeleteLog extends Job
{
    protected $log;

    /**
     * Create a new job instance.
     *
     * @param  $log
     */
    public function __construct($log)
    {
        $this->log = $log;
    }

    /**
     * Execute the job.
     *
     * @return boolean|Exception
     */
    public function handle()
    {
        return $this->log->delete();
    }
}

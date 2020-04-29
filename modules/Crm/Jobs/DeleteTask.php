<?php

namespace Modules\Crm\Jobs;

use App\Abstracts\Job;

class DeleteTask extends Job
{
    protected $task;

    /**
     * Create a new job instance.
     *
     * @param  $task
     */
    public function __construct($task)
    {
        $this->task = $task;
    }

    /**
     * Execute the job.
     *
     * @return boolean|Exception
     */
    public function handle()
    {
        return $this->task->delete();
    }
}

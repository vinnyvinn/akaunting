<?php

namespace Modules\Crm\Jobs;

use App\Abstracts\Job;
use Modules\Crm\Models\Task;

class UpdateTask extends Job
{
    protected $task;

    protected $request;

    /**
     * Create a new job instance.
     *
     * @param  $task
     * @param  $request
     */
    public function __construct($task, $request)
    {
        $this->task = $task;
        $this->request = $this->getRequestInstance($request);
    }

    /**
     * Execute the job.
     *
     * @return Task
     */
    public function handle()
    {
        $this->task->update($this->request->all());

        return $this->task;
    }
}

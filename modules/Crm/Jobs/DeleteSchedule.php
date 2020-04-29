<?php

namespace Modules\Crm\Jobs;

use App\Abstracts\Job;

class DeleteSchedule extends Job
{
    protected $schedule;

    /**
     * Create a new job instance.
     *
     * @param  $schedule
     */
    public function __construct($schedule)
    {
        $this->schedule = $schedule;
    }

    /**
     * Execute the job.
     *
     * @return boolean|Exception
     */
    public function handle()
    {
        return $this->schedule->delete();
    }
}

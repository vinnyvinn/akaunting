<?php

namespace Modules\Crm\Jobs;

use App\Abstracts\Job;
use Modules\Crm\Models\Schedule;

class UpdateSchedule extends Job
{
    protected $schedule;

    protected $request;

    /**
     * Create a new job instance.
     *
     * @param  $schedule
     * @param  $request
     */
    public function __construct($schedule, $request)
    {
        $this->schedule = $schedule;
        $this->request = $this->getRequestInstance($request);
    }

    /**
     * Execute the job.
     *
     * @return Schedule
     */
    public function handle()
    {
        $this->schedule->update($this->request->all());

        return $this->schedule;
    }
}

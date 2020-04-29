<?php

namespace Modules\Crm\Jobs;

use App\Abstracts\Job;
use Modules\Crm\Models\Schedule;
use Modules\Crm\Models\Contact;
use Modules\Crm\Models\Company;

class CreateSchedule extends Job
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
     * @return Contact
     */
    public function handle()
    {
        if ($this->request->get('type') == 'contacts') {
            $row = Contact::find($this->request->get('id'))->schedules();
        } else {
            $row = Company::find($this->request->get('id'))->schedules();
        }

        $schedule = new Schedule;

        $schedule->company_id = $this->request->get('company_id');
        $schedule->created_by = $this->request->get('created_by');
        $schedule->type = $this->request->get('schedule_type');
        $schedule->name = $this->request->get('name');
        $schedule->description = $this->request->get('description');
        $schedule->started_at = $this->request->get('started_at');
        $schedule->started_time_at = $this->request->get('started_time_at');
        $schedule->ended_at = $this->request->get('ended_at');
        $schedule->ended_time_at = $this->request->get('ended_time_at');
        $schedule->user_id = $this->request->get('user_id');

        $schedule = $row->save($schedule);

        return $schedule;
    }
}

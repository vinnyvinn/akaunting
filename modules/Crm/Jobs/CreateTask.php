<?php

namespace Modules\Crm\Jobs;

use App\Abstracts\Job;
use Modules\Crm\Models\Task;
use Modules\Crm\Models\Contact;
use Modules\Crm\Models\Company;

class CreateTask extends Job
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
            $row = Contact::find($this->request->get('id'))->tasks();
        } else {
            $row = Company::find($this->request->get('id'))->tasks();
        }

        $task = new Task;

        $task->company_id = $this->request->get('company_id');
        $task->created_by = $this->request->get('created_by');
        $task->name = $this->request->get('name');
        $task->description = $this->request->get('description');
        $task->user_id = $this->request->get('user_id');
        $task->started_at = $this->request->get('started_at');
        $task->started_time_at = $this->request->get('started_time_at');

        $task = $row->save($task);

        return $task;
    }
}

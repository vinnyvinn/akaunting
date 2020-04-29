<?php

namespace Modules\Crm\Jobs;

use App\Abstracts\Job;
use Modules\Crm\Models\Log;
use Modules\Crm\Models\Contact;
use Modules\Crm\Models\Company;

class CreateLog extends Job
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
            $row = Contact::find($this->request->get('id'))->logs();
        } else {
            $row = Company::find($this->request->get('id'))->logs();
        }

        $log = new Log;

        $log->company_id = $this->request->get('company_id');
        $log->created_by = $this->request->get('created_by');
        $log->type = $this->request->get('log_type');
        $log->date = $this->request->get('date');
        $log->time = $this->request->get('time');
        $log->subject = $this->request->get('subject');
        $log->description = $this->request->get('description');

        $log = $row->save($log);

        return $log;
    }
}

<?php

namespace Modules\Crm\Jobs;

use App\Abstracts\Job;
use Modules\Crm\Models\Email;
use Modules\Crm\Models\Contact;
use Modules\Crm\Models\Company;
use Modules\Crm\Models\Deal;

class CreateEmail extends Job
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
            $row = Contact::find($this->request->get('id'))->emails();
        } else if ($this->request->get('type') == 'companies') {
            $row = Company::find($this->request->get('id'))->emails();
        } else {
            $row = Deal::find($this->request->get('id'))->emails();
        }

        $email = new Email;

        $email->company_id = $this->request->get('company_id');
        $email->created_by = $this->request->get('created_by');
        $email->from = $this->request->get('from');
        $email->to = $this->request->get('to');
        $email->subject = $this->request->get('subject');
        $email->body = $this->request->get('body');

        $email = $row->save($email);

        return $email;
    }
}

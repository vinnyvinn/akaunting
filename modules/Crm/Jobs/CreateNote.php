<?php

namespace Modules\Crm\Jobs;

use App\Abstracts\Job;
use Modules\Crm\Models\Note;
use Modules\Crm\Models\Contact;
use Modules\Crm\Models\Company;
use Modules\Crm\Models\Deal;

class CreateNote extends Job
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
            $row = Contact::find($this->request->get('id'))->notes();
        } else if ($this->request->get('type') == 'companies') {
            $row = Company::find($this->request->get('id'))->notes();
        } else {
            $row = Deal::find($this->request->get('id'))->notes();
        }

        $note = new Note;

        $note->company_id = $this->request->get('company_id');
        $note->created_by = $this->request->get('created_by');
        $note->message = $this->request->get('message');

        $note = $row->save($note);

        return $note;
    }
}

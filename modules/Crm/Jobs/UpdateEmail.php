<?php

namespace Modules\Crm\Jobs;

use App\Abstracts\Job;
use Modules\Crm\Models\Email;

class UpdateEmail extends Job
{
    protected $email;

    protected $request;

    /**
     * Create a new job instance.
     *
     * @param  $email
     * @param  $request
     */
    public function __construct($email, $request)
    {
        $this->email = $email;
        $this->request = $this->getRequestInstance($request);
    }

    /**
     * Execute the job.
     *
     * @return Email
     */
    public function handle()
    {
        $this->email->update($this->request->all());

        return $this->email;
    }
}

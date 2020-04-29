<?php

namespace Modules\Crm\Jobs;

use App\Abstracts\Job;

class DeleteEmail extends Job
{
    protected $email;

    /**
     * Create a new job instance.
     *
     * @param  $email
     */
    public function __construct($email)
    {
        $this->email = $email;
    }

    /**
     * Execute the job.
     *
     * @return boolean|Exception
     */
    public function handle()
    {
        return $this->email->delete();
    }
}

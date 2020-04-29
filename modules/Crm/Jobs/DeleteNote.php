<?php

namespace Modules\Crm\Jobs;

use App\Abstracts\Job;

class DeleteNote extends Job
{
    protected $note;

    /**
     * Create a new job instance.
     *
     * @param  $note
     */
    public function __construct($note)
    {
        $this->note = $note;
    }

    /**
     * Execute the job.
     *
     * @return boolean|Exception
     */
    public function handle()
    {
        return $this->note->delete();
    }
}

<?php

namespace Modules\Crm\Jobs;

use App\Abstracts\Job;
use Modules\Crm\Models\Note;

class UpdateNote extends Job
{
    protected $note;

    protected $request;

    /**
     * Create a new job instance.
     *
     * @param  $note
     * @param  $request
     */
    public function __construct($note, $request)
    {
        $this->note = $note;
        $this->request = $this->getRequestInstance($request);
    }

    /**
     * Execute the job.
     *
     * @return Note
     */
    public function handle()
    {
        $this->note->update($this->request->all());

        return $this->note;
    }
}

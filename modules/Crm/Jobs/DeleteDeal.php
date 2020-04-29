<?php

namespace Modules\Crm\Jobs;

use App\Abstracts\Job;

class DeleteDeal extends Job
{
    protected $deal;

    /**
     * Create a new job instance.
     *
     * @param  $deal
     */
    public function __construct($deal)
    {
        $this->deal = $deal;
    }

    /**
     * Execute the job.
     *
     * @return boolean|Exception
     */
    public function handle()
    {
        $this->authorize();

        $this->deal->delete();

        return true;
    }

    /**
     * Determine if this action is applicable.
     *
     * @return void
     */
    public function authorize()
    {
        if ($relationships = $this->getRelationships()) {
            $message = trans('messages.warning.deleted', ['name' => $this->deal->name, 'text' => implode(', ', $relationships)]);

            throw new \Exception($message);
        }
    }

    public function getRelationships()
    {
        $rels = [
        ];

        return $this->countRelationships($this->deal, $rels);
    }
}

<?php

namespace Modules\Crm\Jobs;

use App\Abstracts\Job;

class DeleteCompanyContact extends Job
{
    protected $company_contact;

    /**
     * Create a new job instance.
     *
     * @param  $deal_competitor
     */
    public function __construct($company_contact)
    {
        $this->company_contact = $company_contact;
    }

    /**
     * Execute the job.
     *
     * @return boolean|Exception
     */
    public function handle()
    {
        $this->authorize();

        $this->company_contact->delete();

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
            $message = trans('messages.warning.deleted', ['name' => $this->company_contact->name, 'text' => implode(', ', $relationships)]);

            throw new \Exception($message);
        }
    }

    public function getRelationships()
    {
        $rels = [
        ];

        return $this->countRelationships($this->company_contact, $rels);
    }
}

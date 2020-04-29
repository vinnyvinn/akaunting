<?php

namespace Modules\Crm\Observers;

use App\Abstracts\Observer;
use App\Traits\Relationships;
use App\Models\Common\Contact as Model;
use Modules\Crm\Models\Company;
use Modules\Crm\Models\Contact;

class Customer extends Observer
{
    use Relationships;

    public function saved(Model $customer)
    {
        $this->created($customer);
    }

    public function created(Model $customer)
    {
        if (request()->get('create_crm', false)) {
            return;
        }

        //Customer change type contact or customer
        if (request('type')) {
            if (request('type') == "company") {
                $value = Contact::where('contact_id', $customer->id)->first();

                if (!empty($value)) {
                    $relationships = $this->countRelationships($value, [
                        'deals' => 'deals',
                    ]);

                    if (empty($relationships)) {
                        $value->delete();
                    } else {
                        $message = trans('crm::general.message.disable_code', [
                            'name' => $value->name,
                            'text' =>  explode(' ', $relationships[0])[0] . ' ' . strtolower(trans_choice('crm::' . explode(' ', $relationships[0])[1], (explode(' ', $relationships[0])[0] > 1) ? 2 : 1))
                        ]);

                        flash($message)->warning();
                    }
                }
            } else {
                $value = Company::where('contact_id', $customer->id)->first();

                if (!empty($value)) {
                    $relationships = $this->countRelationships($value, [
                        'deals' => 'deals',
                    ]);

                    if (empty($relationships)) {
                        $value->delete();
                    } else {
                        $message = trans('crm::general.message.disable_code', [
                            'name' => $value->name,
                            'text' =>  explode(' ', $relationships[0])[0] . ' ' . strtolower(trans_choice('crm::' . explode(' ', $relationships[0])[1], (explode(' ', $relationships[0])[0] > 1) ? 2 : 1))
                        ]);

                        flash($message)->warning();
                    }
                }
            }
        }

        switch (request('type')) {
            case 'company':
                $company = Company::where('company_id', session('company_id'))->where('contact_id', $customer->id)->first();

                if (empty($company)) {
                    Company::create([
                        'company_id' => $customer->company_id,
                        'user' => user()->id,
                        'name' => $customer->name,
                        'email' => $customer->email,
                        'phone' => $customer->phone,
                        'stage' => request()->stage,
                        'owner' => request()->owner,
                        'mobile' => null,
                        'web_site' => $customer->website,
                        'fax_number' => null,
                        'address' => $customer->address,
                        'contact_source' => request()->contact_source,
                        'notes' => null,
                        'contact_id' => $customer->id,
                    ]);
                } else {
                    $company->update([
                        'company_id' => $customer->company_id,
                        'user' => user()->id,
                        'name' => $customer->name,
                        'email' => $customer->email,
                        'phone' => $customer->phone,
                        'stage' => request()->stage,
                        'owner' => request()->owner,
                        'mobile' => null,
                        'web_site' => $customer->website,
                        'fax_number' => null,
                        'address' => $customer->address,
                        'contact_source' => request()->contact_source,
                        'notes' => null,
                        'contact_id' => $customer->id,
                    ]);
                }
                break;

            case 'contact':
                $contact = Contact::where('company_id', session('company_id'))->where('contact_id', $customer->id)->first();

                if (empty($contact)) {
                    Contact::create([
                        'company_id' => $customer->company_id,
                        'user' => user()->id,
                        'name' => $customer->name,
                        'email' => $customer->email,
                        'birth_date' => null,
                        'phone' => $customer->phone,
                        'stage' => request()->stage,
                        'owner' => request()->owner,
                        'mobile' => null,
                        'web_site' => $customer->website,
                        'fax_number' => null,
                        'address' => $customer->address,
                        'contact_source' => request()->contact_source,
                        'notes' => null,
                        'contact_id' => $customer->id,
                    ]);
                } else {
                    $contact->update([
                        'company_id' => $customer->company_id,
                        'user' => user()->id,
                        'name' => $customer->name,
                        'email' => $customer->email,
                        'birth_date' => null,
                        'phone' => $customer->phone,
                        'stage' => request()->stage,
                        'owner' => request()->owner,
                        'mobile' => null,
                        'web_site' => $customer->website,
                        'fax_number' => null,
                        'address' => $customer->address,
                        'contact_source' => request()->contact_source,
                        'notes' => null,
                        'contact_id' => $customer->id,
                    ]);
                }
                break;
        }
    }
}

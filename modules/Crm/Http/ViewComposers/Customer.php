<?php

namespace Modules\Crm\Http\ViewComposers;

use Illuminate\View\View;
use Modules\Crm\Models\Company\Company;
use Modules\Crm\Models\Contact\Contact;

class Customer
{
    /**
     * Bind data to the view.
     *
     * @param View $view
     * @return void
     */
    public function compose(View $view)
    {
        return;

        // Make sure it's installed
        if (!env('APP_INSTALLED') && (env('APP_ENV') !== 'testing')) {
            return;
        }

        //
        $types = [
            'contact' => 'Contact',
            'company' => 'Company'
        ];

        $sources = [
            'advert' => trans('crm::general.sources.advert'),
            'chat' => trans('crm::general.sources.chat'),
            'contact_form' => trans('crm::general.sources.contact_form'),
            'employee_referral' => trans('crm::general.sources.employee_referral'),
            'external_referral' => trans('crm::general.sources.external_referral'),
            'marketing_campaign' => trans('crm::general.sources.marketing_campaign'),
            'newsletter' => trans('crm::general.sources.newsletter'),
            'online_store' => trans('crm::general.sources.online_store'),
            'optin_form' => trans('crm::general.sources.optin_form'),
            'partner' => trans('crm::general.sources.partner'),
            'phone' => trans('crm::general.sources.phone'),
            'public_relations' => trans('crm::general.sources.public_relations'),
            'sales_mail_alias' => trans('crm::general.sources.sales_mail_alias'),
            'search_engine' => trans('crm::general.sources.search_engine'),
            'seminar_internal' => trans('crm::general.sources.seminar_internal'),
            'seminar_partner' => trans('crm::general.sources.seminar_partner'),
            'social_media' => trans('crm::general.sources.social_media'),
            'trade_show' => trans('crm::general.sources.trade_show'),
            'web_download' => trans('crm::general.sources.web_download'),
            'web_research' => trans('crm::general.sources.web_research')
        ];

        $stages = [
            'customer' => trans('crm::general.stage.customer'),
            'lead' => trans('crm::general.stage.lead'),
            'opportunity' => trans('crm::general.stage.opportunity'),
            'subscriber' => trans('crm::general.stage.subscriber')
        ];

        $users = \App\Models\Common\Company::find(session('company_id'))->users()->pluck('name', 'id');

        //Edit
        $value = false;

        if ($view->getName() == 'sales.customers.edit') {
            $customer = $view->getData()['customer'];

            $value = Contact::where('core_customer_id', $customer->id)->first();

            if (empty($value)) {
                $value = Company::where('core_customer_id', $customer->id)->first();
            }
        }

        $push_location = 'create_user_input_start';
        $view_path = 'crm::partials.customer.customer';

        // Override the whole file
        $view->getFactory()->startPush($push_location, view($view_path, compact('value', 'types', 'stages', 'sources', 'users')));
    }
}

<?php

namespace Modules\Crm\Traits;

trait Contacts
{
    public function getContactTypes($return = 'array')
    {
        //$types = (string) setting('contact.type.customer', 'customer');
        $types = (string) 'crm_contact';

        return ($return == 'array') ? explode(',', $types) : $types;
    }

    public function getCompanyTypes($return = 'array')
    {
        $types = (string) setting('contact.type.vendor', 'vendor');

        return ($return == 'array') ? explode(',', $types) : $types;
    }

    public function getStages()
    {
        return [
            'customer' => trans('crm::general.stage.customer'),
            'lead' => trans('crm::general.stage.lead'),
            'opportunity' => trans('crm::general.stage.opportunity'),
            'subscriber' => trans('crm::general.stage.subscriber')
        ];
    }

    public function getSources()
    {
        return [
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
    }
}

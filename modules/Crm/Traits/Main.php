<?php

namespace Modules\Crm\Traits;

trait Main
{

    public function getTypes($type = 'logs')
    {
        if ($type != 'logs') {
            return [
                'call' => trans('crm::general.log_type.call'),
                'meeting' => trans('crm::general.log_type.meeting'),
            ];
        }

        return [
            'call' => trans('crm::general.log_type.call'),
            'meeting' => trans('crm::general.log_type.meeting'),
            'email' => trans('crm::general.log_type.email'),
            'sms' => trans('crm::general.log_type.sms'),
        ];
    }
}

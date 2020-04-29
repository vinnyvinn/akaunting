<?php

namespace Modules\Crm\Traits;

trait Schedules
{

    public function getTypes()
    {
        return  [
            'all' => trans('general.all'),
            'email' => trans('crm::general.email'),
            'note' => trans('crm::general.note'),
            'log' => trans_choice('crm::general.logs',1),
            'schedule' => trans_choice('crm::general.schedule', 1),
            'task' => trans('crm::general.task'),
            'deal_activities' => trans('crm::general.deal_activities'),
        ];
    }
}

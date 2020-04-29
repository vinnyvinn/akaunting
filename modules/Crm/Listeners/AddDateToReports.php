<?php

namespace Modules\Crm\Listeners;

use App\Listeners\Report\AddDate as Listener;

class AddDateToReports extends Listener
{
    protected $classes = [
        'Modules\Crm\Reports\Activity',
        'Modules\Crm\Reports\Growth',
    ];
}

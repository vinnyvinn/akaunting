<?php

namespace Modules\Crm\Widgets;

use App\Abstracts\Widget;
use App\Traits\DateTime;
use Modules\Crm\Models\Schedule;
use Date;

class TodaySchedule extends Widget
{
    use DateTime;

    public $today;

    public $default_name = 'crm::widgets.today_schedule';

    public function show()
    {
        $this->today = Date::today();

        $schedules = Schedule::all();

        $today = Date::today()->toDateString();

        return $this->view('crm::widgets.today_schedule', [
            'schedules' => $schedules,
            'today' => $today,
        ]);
    }
}

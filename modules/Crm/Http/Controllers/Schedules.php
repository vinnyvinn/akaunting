<?php

namespace Modules\Crm\Http\Controllers;

use App\Abstracts\Http\Controller;
use Modules\Crm\Models\Schedule;
use App\Models\Common\Company;
use Date;
use Modules\Crm\Traits\Main;

class Schedules extends Controller
{
    use Main;

    public function index()
    {
        $items = [];

        $company_id = session('company_id');

        $schedules = Schedule::all();

        $users = Company::find($company_id)->users()->pluck('name', 'id');

        foreach ($schedules as $schedule) {
            $table = 'contact';

            if ($schedule->scheduleable_type == 'Modules\Crm\Models\Company') {
                $table = 'company';
            }

            $title = $schedule->started_time_at . ' ' . trans('crm::general.log_type.' . $schedule->type);

            $color = '#03c756';

            if (Date::parse($schedule->started_at)->format('Y-m-d') < Date::today()->format('Y-m-d') && Date::parse($schedule->ended_at)->format('Y-m-d') < Date::today()->format('Y-m-d')) {
                $color = '#dd4b39';
            }

            $items[] = [
                'title' => $title,
                'start' => Date::parse($schedule->started_at)->format('Y-m-d'),
                'end' => Date::parse($schedule->ended_at)->format('Y-m-d'),
                'type' => 'schedule',
                'id' => $schedule->id,
                'url' => '',
                'table' => $table,
                'backgroundColor' => $color,
                'borderColor' => $color,
            ];
        }

        $types = $this->getTypes('schedules');

        return view('crm::schedules.index', compact('schedules', 'users', 'items', 'types'));
    }
}

<?php

namespace Modules\Crm\Imports\Activities;

use Modules\Crm\Imports\Activities\Sheets\DealActivity;
use Modules\Crm\Imports\Activities\Sheets\Email;
use Modules\Crm\Imports\Activities\Sheets\Log;
use Modules\Crm\Imports\Activities\Sheets\Note;
use Modules\Crm\Imports\Activities\Sheets\Schedule;
use Modules\Crm\Imports\Activities\Sheets\Task;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class Activities implements WithMultipleSheets
{

    public function sheets(): array
    {
        return [
            'deal_activity' => new DealActivity(),
            'email' => new Email(),
            'log' => new Log(),
            'note' => new Note(),
            'schedule' => new Schedule(),
            'task' => new Task(),
        ];
    }
}

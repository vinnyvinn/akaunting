<?php

namespace Modules\Crm\Exports\Activities;

use Modules\Crm\Exports\Activities\Sheets\DealActivity;
use Modules\Crm\Exports\Activities\Sheets\Email;
use Modules\Crm\Exports\Activities\Sheets\Log;
use Modules\Crm\Exports\Activities\Sheets\Note;
use Modules\Crm\Exports\Activities\Sheets\Schedule;
use Modules\Crm\Exports\Activities\Sheets\Task;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class Activities implements WithMultipleSheets
{
    public $ids;

    public function __construct($ids = null)
    {
        $this->ids = $ids;
    }

    public function sheets(): array
    {
        return [
            'deal_activity' => new DealActivity($this->ids),
            'email' => new Email($this->ids),
            'log' => new Log($this->ids),
            'note' => new Note($this->ids),
            'schedule' => new Schedule($this->ids),
            'task' => new Task($this->ids),
        ];
    }
}

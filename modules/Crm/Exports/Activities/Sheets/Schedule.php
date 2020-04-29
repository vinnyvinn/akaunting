<?php

namespace Modules\Crm\Exports\Activities\Sheets;

use App\Abstracts\Export;
use Modules\Crm\Models\Schedule as Model;

class Schedule extends Export
{
    public function collection()
    {
        $model = Model::usingSearchString(request('search'));

        if (!empty($this->ids)) {
            $model->whereIn('id', (array) $this->ids);
        }

        return $model->get();
    }

    public function map($model): array
    {
        return parent::map($model);
    }

    public function fields(): array
    {
        return [

            'created_by',
            'scheduleable_id',
            'scheduleable_type',
            'type',
            'name',
            'description',
            'started_at',
            'started_time_at',
            'ended_at',
            'ended_time_at',
            'user_id'
        ];
    }
}

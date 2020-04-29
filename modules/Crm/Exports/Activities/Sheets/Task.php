<?php

namespace Modules\Crm\Exports\Activities\Sheets;

use App\Abstracts\Export;
use Modules\Crm\Models\Task as Model;

class Task extends Export
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
            'taskable_id',
            'taskable_type',
            'name',
            'description',
            'user_id',
            'started_at',
            'started_time_at',
        ];
    }
}

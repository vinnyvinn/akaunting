<?php

namespace Modules\Crm\Exports\Activities\Sheets;

use App\Abstracts\Export;
use Modules\Crm\Models\DealActivity as Model;

class DealActivity extends Export
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
            'deal_id',
            'activity_type',
            'name',
            'date',
            'time',
            'duration',
            'assigned',
            'note',
        ];
    }
}

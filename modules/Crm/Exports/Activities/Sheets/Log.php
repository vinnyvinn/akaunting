<?php

namespace Modules\Crm\Exports\Activities\Sheets;

use App\Abstracts\Export;
use Modules\Crm\Models\Log as Model;

class Log extends Export
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
            'logable_id',
            'logable_type',
            'type',
            'date',
            'time',
            'subject',
            'description',
        ];
    }
}

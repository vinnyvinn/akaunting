<?php

namespace Modules\Crm\Exports\Activities\Sheets;

use App\Abstracts\Export;
use Modules\Crm\Models\Email as Model;

class Email extends Export
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
            'emailable_id',
            'emailable_type',
            'from',
            'to',
            'subject',
            'body'
        ];
    }
}

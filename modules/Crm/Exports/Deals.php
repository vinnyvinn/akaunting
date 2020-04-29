<?php

namespace Modules\Crm\Exports;

use App\Abstracts\Export;
use App\Models\Common\Contact as Model;

class Deals extends Export
{
    public function collection()
    {
        $model = Model::type('crm_contact')->usingSearchString(request('search'));

        if (!empty($this->ids)) {
            $model->whereIn('id', (array) $this->ids);
        }

        return $model->get();
    }

    public function fields(): array
    {
        return [
            'name',
            'email',
            'tax_number',
            'phone',
            'address',
            'website',
            'currency_code',
            'reference',
            'enabled',
            'user_id',
        ];
    }
}

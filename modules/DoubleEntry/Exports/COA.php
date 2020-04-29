<?php

namespace Modules\DoubleEntry\Exports;

use App\Abstracts\Export;
use Modules\DoubleEntry\Models\Account as Model;

class COA extends Export
{
    public function collection()
    {
        $model = Model::all();

        return $model;
    }

    public function fields(): array
    {
        return [
            'type_id',
            'code',
            'name',
            'description',
            'enabled'
        ];
    }
}

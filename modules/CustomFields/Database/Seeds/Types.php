<?php

namespace Modules\CustomFields\Database\Seeds;

use App\Abstracts\Model;
use Illuminate\Database\Seeder;
use Modules\CustomFields\Models\Type;

class Types extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->create();

        Model::reguard();
    }

    private function create()
    {
        $rows = [
            [
                'name' =>  trans('custom-fields::general.type.select'),
                'type' => 'select',
            ],
            [
                'name' => trans('custom-fields::general.type.radio'),
                'type' => 'radio',
            ],
            [
                'name' => trans('custom-fields::general.type.checkbox'),
                'type' => 'checkbox',
            ],
            [
                'name' => trans('custom-fields::general.type.text'),
                'type' => 'text',
            ],
            [
                'name' => trans('custom-fields::general.type.textarea'),
                'type' => 'textarea',
            ],
            [
                'name' => trans('custom-fields::general.type.date'),
                'type' => 'date',
            ],
            [
                'name' => trans('custom-fields::general.type.time'),
                'type' => 'time',
            ],
            [
                'name' => trans('custom-fields::general.type.date&time'),
                'type' => 'date&time',
            ],
        ];

        foreach ($rows as $row) {
            Type::firstOrCreate($row);
        }
    }
}

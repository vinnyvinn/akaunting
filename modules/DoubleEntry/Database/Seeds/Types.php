<?php

namespace Modules\DoubleEntry\Database\Seeds;

use App\Abstracts\Model;
use Modules\DoubleEntry\Models\Type;
use Illuminate\Database\Seeder;

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
                'name' => 'double-entry::types.current_asset',
                'class_id' => '1',
            ],
            [
                'name' => 'double-entry::types.fixed_asset',
                'class_id' => '1',
            ],
            [
                'name' => 'double-entry::types.inventory',
                'class_id' => '1',
            ],
            [
                'name' => 'double-entry::types.non_current_asset',
                'class_id' => '1',
            ],
            [
                'name' => 'double-entry::types.prepayment',
                'class_id' => '1',
            ],
            [
                'name' => 'double-entry::types.bank_cash',
                'class_id' => '1',
            ],
            [
                'name' => 'double-entry::types.current_liability',
                'class_id' => '2',
            ],
            [
                'name' => 'double-entry::types.liability',
                'class_id' => '2',
            ],
            [
                'name' => 'double-entry::types.non_current_liability',
                'class_id' => '2',
            ],
            [
                'name' => 'double-entry::types.depreciation',
                'class_id' => '3',
            ],
            [
                'name' => 'double-entry::types.direct_costs',
                'class_id' => '3',
            ],
            [
                'name' => 'double-entry::types.expense',
                'class_id' => '3',
            ],
            [
                'name' => 'double-entry::types.revenue',
                'class_id' => '4',
            ],
            [
                'name' => 'double-entry::types.sales',
                'class_id' => '4',
            ],
            [
                'name' => 'double-entry::types.other_income',
                'class_id' => '4',
            ],
            [
                'name' => 'double-entry::types.equity',
                'class_id' => '5',
            ],
            [
                'name' => 'double-entry::types.tax',
                'class_id' => '2',
            ],
        ];

        foreach ($rows as $row) {
            Type::firstOrCreate($row);
        }
    }
}

<?php

namespace Modules\Crm\Database\Seeds;

use App\Abstracts\Model;
use App\Models\Common\Report;
use Illuminate\Database\Seeder;

class Reports extends Seeder
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
        $company_id = $this->command->argument('company');

        $rows = [
            [
                'company_id' => $company_id,
                'class' => 'Modules\Crm\Reports\Activity',
                'name' => trans('crm::general.activity'),
                'description' => trans('crm::general.activity_description'),
                'settings' => ['period' => 'quarterly','chart' => 'line'],
            ],
            [
                'company_id' => $company_id,
                'class' => 'Modules\Crm\Reports\Growth',
                'name' => trans('crm::general.growth'),
                'description' => trans('crm::general.growth_description'),
                'settings' => ['period' => 'quarterly','chart' => 'line'],
            ]
        ];

        foreach ($rows as $row) {
            Report::create($row);
        }
    }
}

<?php

namespace Modules\Inventory\Database\Seeds;

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
                'class' => 'App\Reports\IncomeSummary',
                'name' => trans('inventory::general.reports.name.income_summary'),
                'description' => trans('inventory::general.reports.description.income_summary'),
                'settings' => ['group' => 'item', 'period' => 'monthly', 'basis' => 'accrual', 'chart' => 'line'],
            ],
            [
                'company_id' => $company_id,
                'class' => 'App\Reports\ExpenseSummary',
                'name' => trans('inventory::general.reports.name.expense_summary'),
                'description' => trans('inventory::general.reports.description.expense_summary'),
                'settings' => ['group' => 'item', 'period' => 'monthly', 'basis' => 'accrual', 'chart' => 'line'],
            ],
            [
                'company_id' => $company_id,
                'class' => 'App\Reports\IncomeExpenseSummary',
                'name' => trans('inventory::general.reports.name.income_expense'),
                'description' => trans('inventory::general.reports.description.income_expense'),
                'settings' => ['group' => 'item', 'period' => 'monthly', 'basis' => 'accrual', 'chart' => 'line'],
            ],
            [
                'company_id' => $company_id,
                'class' => 'App\Reports\ProfitLoss',
                'name' => trans('inventory::general.reports.name.profit_loss'),
                'description' => trans('inventory::general.reports.description.profit_loss'),
                'settings' => ['group' => 'item', 'period' => 'quarterly', 'basis' => 'accrual'],
            ],
        ];

        foreach ($rows as $row) {
            Report::create($row);
        }
    }
}

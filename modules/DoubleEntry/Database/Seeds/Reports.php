<?php

namespace Modules\DoubleEntry\Database\Seeds;

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
                'class' => 'Modules\DoubleEntry\Reports\GeneralLedger',
                'name' => trans('double-entry::general.general_ledger'),
                'description' => trans('double-entry::demo.reports.description.general_ledger'),
                'settings' => [],
            ],
            [
                'company_id' => $company_id,
                'class' => 'Modules\DoubleEntry\Reports\BalanceSheet',
                'name' => trans('double-entry::general.balance_sheet'),
                'description' => trans('double-entry::demo.reports.description.balance_sheet'),
                'settings' => [],
            ],
            [
                'company_id' => $company_id,
                'class' => 'Modules\DoubleEntry\Reports\TrialBalance',
                'name' => trans('double-entry::general.trial_balance'),
                'description' => trans('double-entry::demo.reports.description.trial_balance'),
                'settings' => [],
            ],
            [
                'company_id' => $company_id,
                'class' => 'App\Reports\IncomeSummary',
                'name' => trans('double-entry::demo.reports.name.income_summary'),
                'description' => trans('double-entry::demo.reports.description.income_summary'),
                'settings' => ['group' => 'de_account', 'period' => 'monthly', 'basis' => 'accrual', 'chart' => 'line'],
            ],
            [
                'company_id' => $company_id,
                'class' => 'App\Reports\ExpenseSummary',
                'name' => trans('double-entry::demo.reports.name.expense_summary'),
                'description' => trans('double-entry::demo.reports.description.expense_summary'),
                'settings' => ['group' => 'de_account', 'period' => 'monthly', 'basis' => 'accrual', 'chart' => 'line'],
            ],
            [
                'company_id' => $company_id,
                'class' => 'App\Reports\IncomeExpenseSummary',
                'name' => trans('double-entry::demo.reports.name.income_expense'),
                'description' => trans('double-entry::demo.reports.description.income_expense'),
                'settings' => ['group' => 'de_account', 'period' => 'monthly', 'basis' => 'accrual', 'chart' => 'line'],
            ],
            [
                'company_id' => $company_id,
                'class' => 'App\Reports\ProfitLoss',
                'name' => trans('double-entry::demo.reports.name.profit_loss'),
                'description' => trans('double-entry::demo.reports.description.profit_loss'),
                'settings' => ['group' => 'de_account', 'period' => 'quarterly', 'basis' => 'accrual'],
            ],
        ];

        foreach ($rows as $row) {
            Report::create($row);
        }
    }
}

<?php

namespace Modules\DoubleEntry\Listeners;

use App\Abstracts\Listeners\Report as Listener;
use App\Events\Report\FilterApplying;
use App\Events\Report\FilterShowing;
use App\Events\Report\GroupApplying;
use App\Events\Report\GroupShowing;
use App\Events\Report\RowsShowing;
use App\Traits\Currencies;
use Date;
use Modules\DoubleEntry\Models\Account as Coa;

class AddCoaToCoreReports extends Listener
{
    use Currencies;

    /**
     * Handle filter showing event.
     *
     * @param  $event
     * @return void
     */
    public function handleFilterShowing(FilterShowing $event)
    {
        $classes = [
            'App\Reports\IncomeSummary',
            'App\Reports\ExpenseSummary',
            'App\Reports\IncomeExpenseSummary',
        ];

        if (empty($event->class) || !in_array(get_class($event->class), $classes)) {
            return;
        }

        if ($event->class->model->settings->group != 'category') {
            unset($event->class->filters['categories']);
        }

        switch (get_class($event->class)) {
            case 'App\Reports\IncomeSummary':
                //$types = [6, 13, 14, 15];
                $types = [13, 14, 15];

                break;
            case 'App\Reports\ExpenseSummary':
                //$types = [6, 12];
                $types = [12];

                break;
            case 'App\Reports\IncomeExpenseSummary':
                //$types = [6, 12, 13, 14, 15];
                $types = [12, 13, 14, 15];

                break;
        }

        $de_accounts = Coa::inType($types)->pluck('name', 'id')->transform(function ($name) {
            return trans($name);
        })->sort()->all();

        $event->class->filters['de_accounts'] = $de_accounts;
    }

    /**
     * Handle filter applying event.
     *
     * @param  $event
     * @return void
     */
    public function handleFilterApplying(FilterApplying $event)
    {
        $classes = [
            'App\Reports\IncomeSummary',
            'App\Reports\ExpenseSummary',
            'App\Reports\IncomeExpenseSummary',
        ];

        if (empty($event->class) || !in_array(get_class($event->class), $classes)) {
            return;
        }

        $de_accounts = request('de_accounts', []);

        if (empty($de_accounts)) {
            return;
        }

        $event->model->whereHas('de_ledger', function ($query) use ($de_accounts, $event) {
            $query->whereIn('account_id', (array) $de_accounts);
        });
    }

    /**
     * Handle group showing event.
     *
     * @param  $event
     * @return void
     */
    public function handleGroupShowing(GroupShowing $event)
    {
        $classes = [
            'App\Reports\IncomeSummary',
            'App\Reports\ExpenseSummary',
            'App\Reports\IncomeExpenseSummary',
            'App\Reports\ProfitLoss',
        ];

        if (empty($event->class) || !in_array(get_class($event->class), $classes)) {
            return;
        }

        $event->class->groups['de_account'] = trans_choice('double-entry::general.chart_of_accounts', 1);
    }

    /**
     * Handle group applying event.
     *
     * @param  $event
     * @return void
     */
    public function handleGroupApplying(GroupApplying $event)
    {
        $classes = [
            'App\Reports\IncomeSummary',
            'App\Reports\ExpenseSummary',
            'App\Reports\IncomeExpenseSummary',
            'App\Reports\ProfitLoss',
        ];

        if (empty($event->class) || !in_array(get_class($event->class), $classes)) {
            return;
        }

        $type = false;
        $table = 'default';

        switch ($event->model->getTable()) {
            case 'invoices':
                $items = $event->model->items()->get();

                if (get_class($event->class) == 'App\Reports\ProfitLoss') {
                    $type = 'income';
                    $table = trans_choice('general.incomes', 1);
                }

                break;
            case 'bills':
                $items = $event->model->items()->get();

                if (get_class($event->class) == 'App\Reports\ProfitLoss') {
                    $type = 'expense';
                    $table = trans_choice('general.expenses', 2);
                }

                break;
            case 'transactions':
                $items = $event->model->get();

                if (get_class($event->class) == 'App\Reports\ProfitLoss') {
                    $type = $event->model->type;
                    $table = ($type == 'income') ? trans_choice('general.incomes', 1) : trans_choice('general.expenses', 2);
                }

                break;
        }

        if (empty($items)) {
            return;
        }

        $filter = request('de_accounts', []);

        foreach ($items as $item) {
            $model = $item->de_ledger();

            if (!empty($filter)) {
                $model->whereIn('account_id', (array) $filter);
            }

            $ledger = $model->first();

            if (empty($ledger)) {
                continue;
            }

            $this->setTotals($event, $ledger, $type, $table);
        }
    }

    public function setTotals($event, $ledger, $type, $table)
    {
        $date = $this->getFormattedDate($event, Date::parse($ledger->issued_at));

        if (
            !isset($event->class->row_values[$table][$ledger->account_id])
            || !isset($event->class->row_values[$table][$ledger->account_id][$date])
            || !isset($event->class->footer_totals[$table][$date]))
        {
            return;
        }

        $amount = !empty($ledger->debit) ? $ledger->debit : $ledger->credit;

        if (empty($amount)) {
            return;
        }

        $amount = $this->convertToDefault($amount, $event->model->currency_code, $event->model->currency_rate);

        if (($table == 'default') || ($type == 'income')) {
            $event->class->row_values[$table][$ledger->account_id][$date] += $amount;

            $event->class->footer_totals[$table][$date] += $amount;
        } else {
            $event->class->row_values[$table][$ledger->account_id][$date] -= $amount;

            $event->class->footer_totals[$table][$date] -= $amount;
        }
    }

    /**
     * Handle records showing event.
     *
     * @param  $event
     * @return void
     */
    public function handleRowsShowing(RowsShowing $event)
    {
        if (
            empty($event->class)
            || empty($event->class->model->settings->group)
            || ($event->class->model->settings->group != 'de_account')
        ) {
            return;
        }

        switch (get_class($event->class)) {
            case 'App\Reports\ProfitLoss':
                //$types = [6, 12, 13, 14, 15];
                $types = [12, 13, 14, 15];

                $de_accounts = Coa::inType($types)->get()->transform(function ($account, $key) {
                    $account->name = trans($account->name);
                    return $account;
                });
                $rows = $de_accounts->sortBy('name')->pluck('name', 'id')->toArray();

                $this->setRowNamesAndValuesForProfitLoss($event, $rows, $de_accounts);

                break;
            case 'App\Reports\IncomeSummary':
            case 'App\Reports\ExpenseSummary':
            case 'App\Reports\IncomeExpenseSummary':
                if ($de_accounts = request('de_accounts')) {
                    $rows = collect($event->class->filters['de_accounts'])->filter(function ($value, $key) use ($de_accounts) {
                        return in_array($key, $de_accounts);
                    });
                } else {
                    $rows = $event->class->filters['de_accounts'];
                }

                $this->setRowNamesAndValues($event, $rows);

                break;
        }
    }

    public function setRowNamesAndValuesForProfitLoss($event, $rows, $de_accounts)
    {
        $type_accounts = [
            //'income' => [6, 13, 14, 15],
            'income' => [13, 14, 15],
            //'expense' => [6, 12],
            'expense' => [12],
        ];

        foreach ($event->class->dates as $date) {
            foreach ($event->class->tables as $type_id => $type_name) {
                foreach ($rows as $id => $name) {
                    $de_account = $de_accounts->where('id', $id)->first();

                    if (!in_array($de_account->type_id, $type_accounts[$type_id])) {
                        continue;
                    }

                    $event->class->row_names[$type_name][$id] = $name;
                    $event->class->row_values[$type_name][$id][$date] = 0;
                }
            }
        }
    }
}

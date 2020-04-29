<?php

namespace Modules\DoubleEntry\Reports;

use App\Abstracts\Report;
use Modules\DoubleEntry\Models\DEClass;

class BalanceSheet extends Report
{
    public $default_name = 'double-entry::general.balance_sheet';

    public $category = 'general.accounting';

    public $icon = 'fa fa-balance-scale';

    public $total_equity = 0;

    public function getGrandTotal()
    {
        if (!$this->loaded) {
            $this->load();
        }

        $total = money((double) $this->total_equity, setting('default.currency'), true);

        return $total;
    }

    public function setViews()
    {
        parent::setViews();
        $this->views['content'] = 'double-entry::balance_sheet.content';
    }

    public function setData()
    {
        $accounts = [];

        $income = 0;

        $classes = DEClass::with('types', 'types.accounts')->get()->reject(function ($c) {
            return ($c->name == 'double-entry::classes.expenses');
        });

        foreach ($classes as $class) {
            $class->total = 0;

            foreach ($class->types as $type) {
                $type->total = 0;

                foreach ($type->accounts as $item) {
                    $item->total = abs($item->debit_total - $item->credit_total);

                    if ($class->name == 'double-entry::classes.income') {
                        $income += $item->total;
                        continue;
                    }

                    if ($item->code == '320') {
                        $item->total += $income;
                    }

                    $type->total += $item->total;
                    $class->total += $item->total;

                    $accounts[$type->id][] = $item;
                }
            }

            if ($class->name == 'double-entry::classes.equity') {
                $this->total_equity = $class->total;
            }
        }

        $classes = $classes->reject(function ($c) {
            return ($c->name == 'double-entry::classes.income');
        })->all();

        $this->de_classes = $classes;
        $this->de_accounts = $accounts;
    }

    public function setTables()
    {
        //
    }

    public function setDates()
    {
        //
    }

    public function setRows()
    {
        //
    }

    public function getFields()
    {
        return [];
    }
}

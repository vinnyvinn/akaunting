<?php

namespace Modules\DoubleEntry\Reports;

use App\Abstracts\Report;
use Modules\DoubleEntry\Models\DEClass;

class TrialBalance extends Report
{
    public $default_name = 'double-entry::general.trial_balance';

    public $category = 'general.accounting';

    public $icon = 'fa fa-balance-scale';

    public $indents = [
        'table_header' => '0px',
        'table_rows' => '48px',
    ];

    public function getGrandTotal()
    {
        if (!$this->loaded) {
            $this->load();
        }

        $total = money((double) $this->footer_totals['debit'], setting('default.currency'), true);

        return $total;
    }

    public function setViews()
    {
        parent::setViews();
        $this->views['content.header'] = 'double-entry::trial_balance.content.header';
        $this->views['content.footer'] = 'double-entry::trial_balance.content.footer';
        $this->views['table'] = 'double-entry::trial_balance.table';
        $this->views['table.header'] = 'double-entry::trial_balance.table.header';
        $this->views['table.footer'] = 'double-entry::trial_balance.table.footer';
        $this->views['table.rows'] = 'double-entry::trial_balance.table.rows';
    }

    public function setTables()
    {
        $this->de_classes = DEClass::with('accounts')->get()->transform(function ($class) {
            $class->name = trans($class->name);
            return $class;
        });

        $arr = $this->de_classes->pluck('name')->toArray();

        $this->tables = array_combine($arr, $arr);
    }

    public function setDates()
    {
        $this->footer_totals['debit'] = 0;
        $this->footer_totals['credit'] = 0;
    }

    public function setRows()
    {
        //
    }

    public function setData()
    {
        foreach ($this->de_classes as $class) {
            $this->row_values[$class->name] = [];

            foreach ($class->accounts as $account) {
                $this->row_names[$class->name][$account->id] = trans($account->name);

                $total = $account->debit_total - $account->credit_total;

                if (empty($total)) {
                    continue;
                }

                if ($total < 0) {
                    $debit_total = 0;
                    $credit_total = abs($total);
                } else {
                    $debit_total = abs($total);
                    $credit_total = 0;
                }

                $this->row_values[$class->name][$account->id]['debit'] = $debit_total;
                $this->row_values[$class->name][$account->id]['credit'] = $credit_total;

                $this->footer_totals['debit'] += $debit_total;
                $this->footer_totals['credit'] += $credit_total;
            }
        }
    }

    public function getFields()
    {
        return [];
    }
}

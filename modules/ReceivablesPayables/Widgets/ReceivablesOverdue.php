<?php

namespace Modules\ReceivablesPayables\Widgets;

use App\Abstracts\Widget;
use App\Models\Sale\Invoice;
use Date;

class ReceivablesOverdue extends Widget
{
    public $default_name = 'receivables-payables::general.receivables_overdue';

    public function show()
    {
        $total = 0;

        $periods = [
            'overdue_1_30' => 0,
            'overdue_30_60' => 0,
            'overdue_60_90' => 0,
            'overdue_90_un' => 0,
        ];

        foreach ($periods as $period_name => $period_amount) {
            $arr = explode('_', $period_name);

            if ($arr[2] == 'un') {
                $arr[2] = '9999';
            }

            $start = Date::today()->subDays($arr[2])->toDateString() . ' 00:00:00';
            $end = Date::today()->subDays($arr[1])->toDateString() . ' 23:59:59';

            $model = Invoice::accrued()->notPaid()->whereBetween('due_at', [$start, $end]);

            $this->applyFilters($model, ['date_field' => 'due_at'])->each(function ($invoice) use (&$periods, $period_name, &$total) {
                $payments = 0;

                if ($invoice->status == 'partial') {
                    foreach ($invoice->transactions as $transaction) {
                        $payments += $transaction->getAmountConvertedToDefault();
                    }
                }

                $amount = $invoice->getAmountConvertedToDefault() - $payments;

                $periods[$period_name] += $amount;
                $total += $amount;
            });
        }

        return $this->view('receivables-payables::overdue', [
            'periods' => $periods,
            'total' => $total,
        ]);
    }
}

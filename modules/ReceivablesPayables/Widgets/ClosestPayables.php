<?php

namespace Modules\ReceivablesPayables\Widgets;

use App\Abstracts\Widget;
use App\Models\Purchase\Bill;
use Date;

class ClosestPayables extends Widget
{
    public $default_name = 'receivables-payables::general.closest_payables';

    public function show()
    {
        $model = Bill::with('contact')->accrued()->notPaid()->where('due_at', '<=', Date::today()->toDateTimeString())->orderBy('due_at', 'desc')->take(5);

        $bills = $this->applyFilters($model, ['date_field' => 'due_at'])->get()->transform(function ($bill) {
            $payments = 0;

            if ($bill->status == 'partial') {
                foreach ($bill->transactions as $transaction) {
                    $payments += $transaction->amount;
                }
            }

            $bill->amount = $bill->amount - $payments;

            return $bill;
        })->all();

        return $this->view('receivables-payables::closest_payables', [
            'bills' => $bills,
        ]);
    }
}

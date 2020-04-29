<?php

namespace Modules\ReceivablesPayables\Widgets;

use App\Abstracts\Widget;
use App\Models\Sale\Invoice;
use Date;

class ClosestReceivables extends Widget
{
    public $default_name = 'receivables-payables::general.closest_receivables';

    public function show()
    {
        $model = Invoice::with('contact')->accrued()->notPaid()->where('due_at', '<=', Date::today()->toDateTimeString())->orderBy('due_at', 'desc')->take(5);

        $invoices = $this->applyFilters($model, ['date_field' => 'due_at'])->get()->transform(function ($invoice) {
            $payments = 0;

            if ($invoice->status == 'partial') {
                foreach ($invoice->transactions as $transaction) {
                    $payments += $transaction->amount;
                }
            }

            $invoice->amount = $invoice->amount - $payments;

            return $invoice;
        })->all();

        return $this->view('receivables-payables::closest_receivables', [
            'invoices' => $invoices,
        ]);
    }
}

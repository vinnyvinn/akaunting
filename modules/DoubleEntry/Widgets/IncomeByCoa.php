<?php

namespace Modules\DoubleEntry\Widgets;

use App\Abstracts\Widget;
use App\Traits\Currencies;
use Modules\DoubleEntry\Models\Account as Coa;

class IncomeByCoa extends Widget
{
    use Currencies;

    public $default_name = 'double-entry::widgets.income_by_coa';

    public $default_settings = [
        'width' => 'col-md-6',
    ];

    public function show()
    {
        // income types
        //$types = [6, 13, 14, 15];
        $types = [13, 14, 15];

        Coa::with('ledgers')->inType($types)->enabled()->each(function ($coa) {
            $amount = 0;

            //$model = $coa->ledgers()->whereNotNull('credit')->where('ledgerable_type', 'App\Models\Banking\Transaction'); // only cash
            $model = $coa->ledgers()
                            ->whereNotNull('credit')
                            ->whereHasMorph('ledgerable', ['App\Models\Sale\InvoiceItem', 'App\Models\Banking\Transaction'], function ($query, $type) {
                                if ($type == 'App\Models\Banking\Transaction') {
                                    $query->whereNull('document_id');
                                }
                            });

            $this->applyFilters($model, ['date_field' => 'issued_at'])->get()->each(function ($ledger) use (&$amount) {
                $ledgerable = $ledger->ledgerable;

                switch ($ledgerable->getTable()) {
                    case 'invoice_items':
                        $ledger_amount = $ledger->credit;
                        $currency_code = $ledgerable->invoice->currency_code;
                        $currency_rate = $ledgerable->invoice->currency_rate;

                        break;
                    case 'transactions':
                        $ledger_amount = $ledgerable->amount;
                        $currency_code = $ledgerable->currency_code;
                        $currency_rate = $ledgerable->currency_rate;

                        break;
                }

                $amount += $this->convertToDefault($ledger_amount, $currency_code, $currency_rate);
            });

            $random_color = '#' . dechex(rand(0x000000, 0xFFFFFF));
            //$random_color = '#' . dechex(mt_rant(0, 16777215));

            $this->addMoneyToDonut($random_color, $amount, trans($coa->name));
        });

        $chart = $this->getDonutChart(trans_choice('general.incomes', 1), 0, 160, 6);

        return $this->view('widgets.donut_chart', [
            'chart' => $chart,
        ]);
    }
}

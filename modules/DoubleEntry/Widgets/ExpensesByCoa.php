<?php

namespace Modules\DoubleEntry\Widgets;

use App\Abstracts\Widget;
use App\Traits\Currencies;
use Modules\DoubleEntry\Models\Account as Coa;

class ExpensesByCoa extends Widget
{
    use Currencies;

    public $default_name = 'double-entry::widgets.expenses_by_coa';

    public $default_settings = [
        'width' => 'col-md-6',
    ];

    public function show()
    {
        // expense types
        //$types = [6, 12];
        $types = [12];

        Coa::with('ledgers')->inType($types)->enabled()->each(function ($coa) {
            $amount = 0;

            //$model = $coa->ledgers()->whereNotNull('debit')->where('ledgerable_type', 'App\Models\Banking\Transaction'); // only cash
            $model = $coa->ledgers()
                            ->whereNotNull('debit')
                            ->whereHasMorph('ledgerable', ['App\Models\Purchase\BillItem', 'App\Models\Banking\Transaction'], function ($query, $type) {
                                if ($type == 'App\Models\Banking\Transaction') {
                                    $query->whereNull('document_id');
                                }
                            });


            $this->applyFilters($model, ['date_field' => 'issued_at'])->get()->each(function ($ledger) use (&$amount) {
                $ledgerable = $ledger->ledgerable;

                switch ($ledgerable->getTable()) {
                    case 'bill_items':
                        $ledger_amount = $ledger->debit;
                        $currency_code = $ledgerable->bill->currency_code;
                        $currency_rate = $ledgerable->bill->currency_rate;

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

        $chart = $this->getDonutChart(trans_choice('general.expenses', 2), 0, 160, 6);

        return $this->view('widgets.donut_chart', [
            'chart' => $chart,
        ]);
    }
}

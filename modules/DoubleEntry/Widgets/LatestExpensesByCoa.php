<?php

namespace Modules\DoubleEntry\Widgets;

use App\Abstracts\Widget;
use App\Traits\Currencies;
use Modules\DoubleEntry\Models\Ledger;

class LatestExpensesByCoa extends Widget
{
    use Currencies;

    public $default_name = 'double-entry::widgets.latest_expenses';

    public function show()
    {
        // expense types
        //$types = [6, 12];
        $types = [12];

        $model = Ledger::whereHas('account', function ($query) use ($types) {
                            $query->whereIn('type_id', $types);
                        })
                        ->whereNotNull('debit')
                        ->whereHasMorph('ledgerable', ['App\Models\Purchase\BillItem', 'App\Models\Banking\Transaction'], function ($query, $type) {
                            if ($type == 'App\Models\Banking\Transaction') {
                                $query->whereNull('document_id');
                            }
                        })
                        ->orderBy('issued_at', 'desc')
                        ->take(5);

        $ledgers = $this->applyFilters($model, ['date_field' => 'issued_at'])->get()->transform(function ($ledger) {
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

            $ledger->amount = $this->convertToDefault($ledger_amount, $currency_code, $currency_rate);

            return $ledger;
        })->all();

        return $this->view('double-entry::widgets.latest', [
            'ledgers' => $ledgers,
        ]);
    }
}

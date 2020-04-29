<?php

namespace Modules\DoubleEntry\Http\Middleware;

use Closure;

class Money
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->method() == 'POST' || $request->method() == 'PATCH') {
            $amount = $request->get('amount');
            $currency_code = $request->get('currency_code');
            $items = $request->get('item');

            if (empty($currency_code)) {
                $currency_code = setting('default.currency');
            }

            if (!empty($amount)) {
                $amount = money($request->get('amount'), $currency_code)->getAmount();

                $request->request->set('amount', $amount);
            }

            if (!empty($items)) {
                foreach ($items as $key => $item) {
                    $debit = $credit = 0;

                    if (isset($item['debit'])) {
                        $debit = $item['debit'];
                    }

                    if (isset($item['credit'])) {
                        $credit = $item['credit'];
                    }

                    $items[$key]['debit'] = money($debit, $currency_code)->getAmount();
                    $items[$key]['credit'] = money($credit, $currency_code)->getAmount();
                }

                $request->request->set('item', $items);
            }
        }

        return $next($request);
    }
}

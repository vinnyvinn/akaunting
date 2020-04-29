<?php

namespace Modules\SalesMetrics\Widgets;

use App\Abstracts\Widget;
use App\Models\Banking\Transaction;
use App\Models\Sale\Invoice;

class TopCustomersRevenueBased extends Widget
{
    public $default_name = 'sales-metrics::general.widgets.top_customers_revenue_based';

    /**
     * Calculation logic consists both invoices and revenues
     */
    public function show()
    {
        $customers = collect();

        // Calculation of Invoices
        $this->applyFilters(Invoice::all(), ['date_field' => 'invoiced_at'])
            ->groupBy('contact_id')
            ->each(function ($contact, $key) use ($customers) {
                $amount = 0;

                $amount += $contact->sum(function ($invoice) {
                    return $invoice->getAmountConvertedToDefault();
                });

                $customers->put($key, collect(['contact_id' => $key, 'contact_name' => $contact->first()->contact_name, 'amount' => $amount]));
            });

        // Calculation of Revenues
        $this->applyFilters(Transaction::type('income')->isNotDocument()->whereNotNull('contact_id')->get())
            ->groupBy('contact_id')
            ->each(function ($contact, $key) use ($customers) {
                $amount = 0;

                $amount += $contact->sum(function ($revenue) {
                    return $revenue->getAmountConvertedToDefault();
                });

                if ($customers->has($key)) {
                    $customers->get($key)->put('amount', $customers->get($key)->get('amount') + $amount);
                }
                else {
                    $customers->put($key, collect(['contact_id' => $key, 'contact_name' => $contact->first()->contact->name, 'amount' => $amount]));
                }
            });

        return $this->view('sales-metrics::top_customers_revenue_based', [
            'customers' => $customers->sortByDesc('amount')->take(5),
        ]);
    }
}

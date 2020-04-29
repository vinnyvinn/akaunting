<?php

namespace Modules\SalesMetrics\Widgets;

use App\Abstracts\Widget;
use App\Models\Sale\Invoice;

class TopCustomersProfitBased extends Widget
{
    public $default_name = 'sales-metrics::general.widgets.top_customers_profit_based';

    /**
     * Calculation logic consists only invoices. Revenues are not considered.
     */
    public function show()
    {
        $customers = collect();

        // Calculation of Invoices
        $this->applyFilters(Invoice::with('items.item')->paid()->get(), ['date_field' => 'invoiced_at'])
            ->groupBy('contact_id')
            ->each(function ($contact, $key) use ($customers) {
                $amount = 0;

                $amount += $contact->sum(function ($invoice) {
                    return $invoice->items->sum(function ($invoice_item) {
                        return $invoice_item->total - $invoice_item->item->purchase_price * $invoice_item->quantity;
                    });
                });

                if ($amount > 0) {
                    $customers->put($key, collect(['contact_id' => $key, 'contact_name' => $contact->first()->contact_name, 'amount' => $amount]));
                }
            });

        return $this->view('sales-metrics::top_customers_profit_based', [
            'customers' => $customers->sortByDesc('amount')->take(5),
        ]);
    }
}

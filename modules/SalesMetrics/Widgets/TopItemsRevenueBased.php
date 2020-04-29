<?php

namespace Modules\SalesMetrics\Widgets;

use App\Abstracts\Widget;
use App\Models\Sale\Invoice;

class TopItemsRevenueBased extends Widget
{
    public $default_name = 'sales-metrics::general.widgets.top_items_revenue_based';

    /**
     * Calculation logic consists only invoices.
     */
    public function show()
    {
        $items = collect();

        // Calculation of Invoice items.
        $this->applyFilters(Invoice::with('items')->get(), ['date_field' => 'invoiced_at'])
            ->each(function ($invoice) use ($items) {

                $invoice->items->each(function ($invoice_item) use ($items) {
                    if ($items->has($invoice_item->item_id)) {
                        $items->get($invoice_item->item_id)->put('amount', $items->get($invoice_item->item_id)->get('amount') + $invoice_item->total);
                    } else {
                        $items->put($invoice_item->item_id, collect([
                            'item_id' => $invoice_item->item_id, 
                            'item_name' => $invoice_item->name, 
                            'amount' => $invoice_item->total
                        ]));
                    }
                });

            });

        return $this->view('sales-metrics::top_items_revenue_based', [
            'items' => $items->sortByDesc('amount')->take(5),
        ]);
    }
}

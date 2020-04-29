<?php

namespace Modules\SalesMetrics\Widgets;

use App\Abstracts\Widget;
use App\Models\Common\Item;

class TopItemsProfitMarginBased extends Widget
{
    public $default_name = 'sales-metrics::general.widgets.top_items_profit_margin_based';

    /**
     * Calculation logic consists only items.
     */
    public function show()
    {
        $items = collect();

        // Calculation of Items.
        Item::all()->each(function ($item) use ($items) {
            if ($item->purchase_price > 0) {
                $items->put($item->id, collect(['item_id' => $item->id, 'item_name' => $item->name, 'margin' => ($item->sale_price / $item->purchase_price - 1) * 100]));
            }
        });

        return $this->view('sales-metrics::top_items_profit_margin_based', [
            'items' => $items->sortByDesc('margin')->take(5),
        ]);
    }
}

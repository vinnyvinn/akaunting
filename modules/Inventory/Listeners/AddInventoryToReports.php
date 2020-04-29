<?php

namespace Modules\Inventory\Listeners;

use App\Abstracts\Listeners\Report as Listener;
use App\Events\Report\RowsShowing;
use App\Events\Report\GroupShowing;
use App\Events\Report\FilterShowing;
use App\Events\Report\GroupApplying;
use App\Events\Report\FilterApplying;
use App\Models\Common\Item;

class AddInventoryToReports extends Listener
{
    protected $classes = [
        'App\Reports\IncomeSummary',
        'App\Reports\ExpenseSummary',
        'App\Reports\IncomeExpenseSummary',
        'App\Reports\ProfitLoss',
    ];

    /**
     * Handle group showing event.
     *
     * @param  $event
     * @return void
     */
    public function handleGroupShowing(GroupShowing $event)
    {
        if ($this->skipThisClass($event)) {
            return;
        }

        $event->class->groups['item'] = trans_choice('inventory::general.name', 1);
    }

        /**
     * Handle group applying event.
     *
     * @param  $event
     * @return void
     */
    public function handleGroupApplying(GroupApplying $event)
    {
        if ($this->skipThisClass($event)) {
            return;
        }

        $event->model->item_id = 0;

        if (!in_array($event->model->getTable(), ['invoices', 'bills'])) {
            return;
        }

        $event->model->item_id = $event->model->items()->pluck('item_id')->first();
    }

    /**
     * Handle rows showing event.
     *
     * @param  $event
     * @return void
     */
    public function handleRowsShowing(RowsShowing $event)
    {
        if ($this->skipRowsShowing($event, 'item')) {
            return;
        }

        $item_list = Item::orderBy('name')->pluck('name', 'id')->toArray();

        if ($items = request('items')) {
            $rows = collect($item_list)->filter(function ($value, $key) use ($items) {
                return in_array($key, $items);
            });
        } else {

            $rows = $item_list;
        }

        $this->setRowNamesAndValues($event, $rows);
    }
}

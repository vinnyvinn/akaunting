<?php

namespace Modules\Inventory\Http\ViewComposers;

use Illuminate\View\View;
use Modules\Inventory\Models\Warehouse;
use App\Models\Module\Module;

class Bill
{
    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $modules = Module::all()->pluck('alias')->toArray();

        if (in_array('inventory', $modules)) {
            $warehouses = Warehouse::enabled()->pluck('name', 'id');

            // Push to a stack
            if ($view->getName() == 'expense.bills.edit') {
                $view->getFactory()->startPush('scripts', view('inventory::partials.bill.edit', compact('warehouses')));
            } else {
                $view->getFactory()->startPush('scripts', view('inventory::partials.bill.create', compact('warehouses')));
            }
        }
    }
}

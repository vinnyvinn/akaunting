<?php

namespace Modules\Inventory\Http\ViewComposers;

use Illuminate\View\View;
use App\Models\Module\Module;

class Index
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
            // Push to a stack
            $view->getFactory()->startPush('scripts', view('inventory::partials.item.index'));
        }
    }
}

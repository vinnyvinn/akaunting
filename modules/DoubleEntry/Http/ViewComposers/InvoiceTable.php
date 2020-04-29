<?php

namespace Modules\DoubleEntry\Http\ViewComposers;

use Illuminate\View\View;

class InvoiceTable
{
    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        // Push to a stack
        $view->getFactory()->startPush('name_th_start', view('double-entry::partials.input_th'));
    }
}

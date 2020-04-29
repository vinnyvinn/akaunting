<?php

namespace Modules\CustomFields\Http\ViewComposers;

use Illuminate\View\View;
use Modules\CustomFields\Models\Field;
use Modules\CustomFields\Models\FieldValue;
use Modules\CustomFields\Models\Location;

class InvoiceItems
{
    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        // No need to add suggestions in console
        if (app()->runningInConsole() || !env('APP_INSTALLED')) {
            return;
        }

        $location = Location::where('code', 'sales.invoices')->first();

        $custom_fields = Field::enabled()->orderBy('name')->byLocation($location->id)->get();

        $item_custom_fields_values = [];

        if ($custom_fields) {
            if (!empty($view->getData()['invoice'])) {
                $invoice = $view->getData()['invoice'];
                $invoice_items = $invoice->items;

                foreach ($invoice_items as $index => $invoice_item) {

                    $custom_field_values = FieldValue::record($invoice_item->id, get_class($invoice_item))->get();

                    foreach ($custom_fields as $custom_field) {
                        foreach ($custom_field_values as $custom_field_value) {
                            if ($custom_field_value->field_id == $custom_field->id) {
                                $item_custom_fields_values[$index][$custom_field->code] = $custom_field_value->value;
                            }
                        }
                    }
                }
            } else {
                foreach ($custom_fields as $custom_field) {
                    $item_custom_fields_values[0][$custom_field->code] = '';
                }
            }
        }

        $view->getFactory()->startPush('scripts', view('custom-fields::invoices.script', compact('item_custom_fields_values')));
    }
}

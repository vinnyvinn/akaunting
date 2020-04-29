<?php

namespace Modules\CustomFields\Http\ViewComposers;

use Illuminate\View\View;
use Modules\CustomFields\Models\Field;
use Modules\CustomFields\Models\FieldValue;
use Modules\CustomFields\Models\Location;

class BillItems
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

        $location = Location::where('code', 'purchases.bills')->first();

        $custom_fields = Field::enabled()->orderBy('name')->byLocation($location->id)->get();

        if (!isset($view->getData()['bill'])) {

            $item_custom_fields_values = [];
            $view->getFactory()->startPush('scripts', view('custom-fields::bills.script', compact('item_custom_fields_values')));

            return;
        }


        $item_custom_fields_values = [];

        if ($custom_fields) {
            if (!empty($view->getData()['bill'])) {
                $bill = $view->getData()['bill'];
                $bill_items = $bill->items;

                foreach ($bill_items as $index => $bill_item) {

                    $custom_field_values = FieldValue::record($bill_item->id, get_class($bill_item))->get();

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

        $view->getFactory()->startPush('scripts', view('custom-fields::bills.script', compact('item_custom_fields_values')));
    }
}

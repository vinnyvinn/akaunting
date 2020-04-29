<?php

namespace Modules\CustomFields\Http\ViewComposers;

use Illuminate\View\View;
use Modules\CustomFields\Models\Field;
use Modules\CustomFields\Models\FieldValue;
use Modules\CustomFields\Models\Location;

class Bills
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

        if ($view->getName() == 'purchases.bills.edit') {
            $bill = $view->getData()['bill'];

            $custom_field_values = FieldValue::record($bill->id, get_class($bill))->get();
            $custom_field_item_values = FieldValue::record($bill->id, get_class($bill))->get();
        }

        $skipes = [
            'actions_td_start',
            'actions_button_start',
            'actions_button_end',
            'actions_td_end',

            'name_td_start',
            'name_input_start',
            'name_input_end',
            'name_td_end',

            'quantity_td_start',
            'quantity_input_start',
            'quantity_input_end',
            'quantity_td_end',

            'price_td_start',
            'price_input_start',
            'price_input_end',
            'price_td_end',

            'taxes_td_start',
            'taxes_input_start',
            'taxes_input_end',
            'taxes_td_end',

            'total_td_start',
            'total_input_start',
            'total_input_end',
            'total_td_end',
        ];

        if ($custom_fields) {
            foreach ($custom_fields as $custom_field) {
                if (isset($custom_field_values)) {
                    foreach ($custom_field_values as $custom_field_value) {
                        if ($custom_field_value->field_id == $custom_field->id) {
                            $value = $custom_field_value->value;

                            break;
                        }
                    }
                }

                $compact[] = 'custom_field';
                $attributes = [];

                if ($custom_field->required) {
                    $attributes['required'] = 'required';
                }

                /*if ($custom_field->class) {
                    $attributes['class'] = $custom_field->class;
                }*/

                if ($custom_field->class) {
                    $attributes['col'] = $custom_field->class;
                }

                switch ($custom_field->type->type) {
                    case 'select':
                        $field_type = 'selectGroup';
                        $field_type_options = $custom_field->fieldTypeOption->pluck('value', 'id');
                        $field_type_selected = 0;

                        if (isset($value)) {
                            $field_type_selected = $value;
                        }

                        $compact[] = 'field_type_options';
                        $compact[] = 'field_type_selected';
                        break;
                    case 'radio':
                        $field_type = 'radioGroup';
                        $field_type_options = $custom_field->fieldTypeOption->pluck('value', 'id');
                        $field_type_selected = 0;

                        if (isset($value)) {
                            $field_type_selected = $value;
                        }

                        $compact[] = 'field_type_options';
                        $compact[] = 'field_type_selected';
                        break;
                    case 'checkbox':
                        $field_type = 'checkboxGroup';
                        $field_type_options = $custom_field->fieldTypeOption;
                        $field_type_selected = 0;

                        if (isset($value)) {
                            $field_type_selected = $value;
                        }

                        $compact[] = 'field_type_options';
                        $compact[] = 'field_type_selected';
                        break;
                    case 'text':
                    case 'date':
                    case 'time':
                    case 'date&time':
                        $field_type = 'textGroup';
                        $file_type_value = $custom_field->fieldTypeOption->first()->value;

                        if (isset($value)) {
                            $file_type_value = $value;
                        }

                        $compact[] = 'file_type_value';
                        break;
                    case 'textarea':
                        $field_type = 'textareaGroup';
                        $file_type_value = $custom_field->fieldTypeOption->first()->value;

                        if (isset($value)) {
                            $file_type_value = $value;
                        }

                        $compact[] = 'file_type_value';
                        break;
                }

                $compact[] = 'field_type';
                $compact[] = 'attributes';

                if (strpos($custom_field->fieldLocation->sort_order, 'td') !== false) {
                    $sort_order = $custom_field->fieldLocation->sort_order;

                    $sort_orders = explode('_td_input', $sort_order);

                    $table_name = $sort_orders[0] . '_th' . $sort_orders[1];
                    $table_item = $sort_orders[0] . '_td' . $sort_orders[1];

                    $table_name_view = 'custom-fields::bills.item_th';
                    $table_item_view = 'custom-fields::bills.item_td_input';

                    $view->getFactory()->startPush($table_name, view($table_name_view, compact($compact)));

                    if (($view->getName() == 'purchases.bills.create' || $view->getName() == 'purchases.bills.edit') && in_array($table_item, $skipes)) {
                        continue;
                    }

                    $action = 'create';

                    if (basename(request()->getPathInfo()) == 'edit') {
                        $action = 'edit';
                    }

                    $compact[] = 'action';

                    $view->getFactory()->startPush($table_item, view($table_item_view, compact($compact)));
                } else {
                    if ($view->getName() == 'purchases.bills.item') {
                        continue;
                    }

                    // Push to a stack
                    $view->getFactory()->startPush($custom_field->fieldLocation->sort_order, view('custom-fields::field', compact($compact)));
                }
            }
        }
    }
}

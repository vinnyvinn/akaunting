<?php

namespace Modules\CustomFields\Http\ViewComposers;

use Illuminate\View\View;
use Modules\CustomFields\Models\Field;
use Modules\CustomFields\Models\FieldValue;
use Modules\CustomFields\Models\Location;
use App\Models\Common\Contact;
use App\Models\Common\Item;

class BillShow
{
    static $td_name = [];
    static $td_item = [];
    static $item_count = 0;

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

        static::$item_count++;

        $bill = $view->getData()['bill'];

        // Set Invoice
        $location = Location::where('code', 'purchases.bills')->first();

        $custom_fields = Field::enabled()->orderBy('name')->byLocation($location->id)->get();

        $bill = $view->getData()['bill'];

        $custom_field_values = FieldValue::record($bill->id, get_class($bill))->get();

        if ($custom_fields) {
            foreach ($custom_fields as $custom_field) {
                if (strpos($custom_field->fieldLocation->sort_order, 'td') !== false) {
                    continue;
                }

                /*if (isset($custom_field->show) && $custom_field->show == 'never') {
                    continue;
                }*/

                $this->setFieldView($view, 'custom-fields::field_show', $custom_field_values, $custom_field);
            }
        }

        // Set Items
        $location = Location::where('code', 'common.items')->first();

        if ($bill->items) {
            foreach ($bill->items as $item) {
                if (empty($item->item_id)) {
                    continue;
                }

                $custom_field_values = FieldValue::record($item->id, get_class($item))->get();

                if ($custom_fields) {
                    foreach ($custom_fields as $custom_field) {
                        if (strpos($custom_field->fieldLocation->sort_order, 'td') === false) {
                            continue;
                        }
                        /*if (isset($custom_field->show) && $custom_field->show == 'never') {
                            continue;
                        }*/

                        $this->setFieldView($view, 'custom-fields::field_show', $custom_field_values, $custom_field);
                    }
                }

                $item_custom_fields = Field::enabled()->orderBy('name')->byLocation($location->id)->get();

                $item = Item::find($item->item_id);

                if ($item) {
                    $item_custom_field_values = FieldValue::record($item->id, 'App\Models\Common\Item')->get();

                    if ($item_custom_fields) {
                        foreach ($item_custom_fields as $item_custom_field) {
                            /*if (isset($custom_field->show) && $custom_field->show == 'never') {
                                continue;
                            }*/

                            if ($item_custom_field->type_id == 3) {
                                continue;
                            }

                            $item_push_locations = [];

                            $sort_order = explode('_input_', $item_custom_field->fieldLocation->sort_order);

                            $item_push_locations['th'] = $sort_order[0] . '_th_' . $sort_order[1];
                            $item_push_locations['td'] = $sort_order[0] . '_td_' . $sort_order[1];

                            foreach ($item_push_locations as $key => $push_location) {
                                $this->setFieldView($view, 'custom-fields::bills.item_' . $key, $item_custom_field_values, $item_custom_field, $push_location);
                            }
                        }
                    }
                }
            }
        }

        // Set Vendor
        $location = Location::where('code', 'purchases.vendors')->first();

        $custom_fields = Field::enabled()->orderBy('name')->byLocation($location->id)->get();

        $vendor = Contact::find($bill->contact_id);

        if ($vendor) {
            $custom_field_values = FieldValue::record($vendor->id, get_class($vendor))->get();

            if ($custom_fields) {
                foreach ($custom_fields as $custom_field) {
                    /*if (isset($custom_field->show) && $custom_field->show == 'never') {
                        continue;
                    }*/

                    $this->setFieldView($view, 'custom-fields::bills.vendor', $custom_field_values, $custom_field);
                }
            }
        }
    }

    protected function setFieldView($view, $view_path = 'custom-fields::field_show', $custom_field_values, $custom_field, $push_location = null)
    {
        if (isset($custom_field_values)) {
            foreach ($custom_field_values as $custom_field_value) {
                if ($custom_field_value->field_id == $custom_field->id) {
                    $value = $custom_field_value->value;

                    break;
                }
            }
        }

        $compact[]  = 'custom_field';
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
                $field_type          = 'selectGroup';
                $field_type_options  = $custom_field->fieldTypeOption->pluck('value', 'id');
                $field_type_selected = 0;
                $file_type_value     = null;

                if (isset($value)) {
                    $field_type_selected = $value;
                }

                if (isset($field_type_options[$field_type_selected])) {
                    $file_type_value = $field_type_options[$field_type_selected];
                }

                $compact[] = 'field_type_options';
                $compact[] = 'field_type_selected';
                $compact[] = 'file_type_value';
                break;
            case 'radio':
                $field_type          = 'radioGroup';
                $field_type_options  = $custom_field->fieldTypeOption->pluck('value', 'id');
                $field_type_selected = 0;
                $file_type_value     = null;

                if (isset($value)) {
                    $field_type_selected = $value;
                }

                if (isset($field_type_options[$field_type_selected])) {
                    $file_type_value = $field_type_options[$field_type_selected];
                }

                $compact[] = 'field_type_options';
                $compact[] = 'field_type_selected';
                $compact[] = 'file_type_value';
                break;
            case 'checkbox':
                $field_type          = 'checkboxGroup';
                $field_type_options  = $custom_field->fieldTypeOption;
                $field_type_selected = 0;
                $file_type_value     = null;

                if (isset($value)) {
                    $field_type_selected = $value;
                }

                if (isset($field_type_options[$field_type_selected])) {
                    $file_type_value = $field_type_options[$field_type_selected];
                }

                $compact[] = 'field_type_options';
                $compact[] = 'field_type_selected';
                $compact[] = 'file_type_value';
                break;
            case 'text':
            case 'date':
            case 'time':
            case 'date&time':
                $field_type      = 'textGroup';
                $file_type_value = $custom_field->fieldTypeOption->first()->value;

                if (isset($value)) {
                    $file_type_value = $value;
                }

                $compact[] = 'file_type_value';
                break;
            case 'textarea':
                $field_type      = 'textareaGroup';
                $file_type_value = $custom_field->fieldTypeOption->first()->value;

                if (isset($value)) {
                    $file_type_value = $value;
                }

                $compact[] = 'file_type_value';
                break;
        }

        $compact[] = 'field_type';
        $compact[] = 'attributes';

        // Set Bill
        //$compact[] = 'bill';

        if (empty($push_location)) {
            $push_location = $custom_field->fieldLocation->sort_order;
        }

        /*if (isset($custom_field->show) && $custom_field->show == 'if_filled' && empty($file_type_value)) {
            return false;
        }*/

        // Push to a stack
        $view->getFactory()->startPush($push_location, view($view_path, compact($compact)));

        if (strpos($custom_field->fieldLocation->sort_order, 'td') !== false) {
            $sort_order = $custom_field->fieldLocation->sort_order;

            $sort_orders = explode('_td_input', $sort_order);

            $table_name = $sort_orders[0] . '_th' . $sort_orders[1];
            $table_item = $sort_orders[0] . '_td' . $sort_orders[1];

            $table_name_view = 'custom-fields::bills.item_th';
            $table_item_view = 'custom-fields::bills.item_td';

            if (!in_array($table_name, static::$td_name)) {
                $view->getFactory()->startPush($table_name, view($table_name_view, compact($compact)));

                static::$td_name[] = $table_name;
            }

            if (!in_array($table_item, static::$td_item)) {
                $view->getFactory()->startPush($table_item, view($table_item_view, compact($compact)));

                static::$td_item[] = $table_item;
            }
        }
    }
}

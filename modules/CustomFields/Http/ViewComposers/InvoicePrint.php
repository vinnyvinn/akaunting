<?php

namespace Modules\CustomFields\Http\ViewComposers;

use Illuminate\View\View;
use Modules\CustomFields\Models\Field;
use Modules\CustomFields\Models\FieldValue;
use Modules\CustomFields\Models\Location;
use App\Models\Common\Contact;
use App\Models\Common\Item;

class InvoicePrint
{

    /**
     * Bind data to the view.
     *
     * @param  View $view
     * @return void
     */
    public function compose(View $view)
    {
        // No need to add suggestions in console
        if (app()->runningInConsole() || !env('APP_INSTALLED')) {
            return;
        }

        $invoice = $view->getData()['invoice'];

        // Set Invoice
        $location = Location::where('code', 'sales.invoices')->first();

        $custom_fields = Field::enabled()->orderBy('name')->byLocation($location->id)->get();

        $invoice = $view->getData()['invoice'];

        $custom_field_values = FieldValue::record($invoice->id, get_class($invoice))->get();

        if ($custom_fields) {
            foreach ($custom_fields as $custom_field) {
                if (strpos($custom_field->fieldLocation->sort_order, 'td') !== false) {
                    continue;
                }
                /*if (isset($custom_field->show) && $custom_field->show == 'never') {
                    continue;
                }*/

                $this->setFieldView($view, 'custom-fields::field_print', $custom_field_values, $custom_field);
            }
        }

        // Set Items
        $location = Location::where('code', 'common.items')->first();

        if ($invoice->items) {
            foreach ($invoice->items as $item) {
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

                $custom_fields = Field::enabled()->orderBy('name')->byLocation($location->id)->get();

                $item = Item::find($item->item_id);

                if ($item) {
                    $custom_field_values = FieldValue::record($item->id, 'App\Models\Common\Item')->get();

                    if ($custom_fields) {
                        foreach ($custom_fields as $custom_field) {
                            /*if (isset($custom_field->show) && $custom_field->show == 'never') {
                                continue;
                            }*/

                            if ($custom_field->type_id == 3) {
                                continue;
                            }

                            $push_locations = [];

                            $sort_order = explode('_input_', $custom_field->fieldLocation->sort_order);

                            $push_locations['th'] = $sort_order[0] . '_th_' . $sort_order[1];
                            $push_locations['td'] = $sort_order[0] . '_td_' . $sort_order[1];

                            foreach ($push_locations as $key => $push_location) {
                                $this->setFieldView($view, 'custom-fields::invoices.item_' . $key, $custom_field_values, $custom_field, $push_location);
                            }
                        }
                    }
                }
            }
        }

        // Set Customer
        $location = Location::where('code', 'sales.customers')->first();

        $custom_fields = Field::enabled()->orderBy('name')->byLocation($location->id)->get();

        $customer = Contact::find($invoice->contact_id);

        if ($customer) {
            $custom_field_values = FieldValue::record($customer->id, get_class($customer))->get();

            if ($custom_fields) {
                foreach ($custom_fields as $custom_field) {
                    /*if (isset($custom_field->show) && $custom_field->show == 'never') {
                        continue;
                    }*/

                    $this->setFieldView($view, 'custom-fields::invoices.customer', $custom_field_values, $custom_field);
                }
            }
        }
    }

    protected function setFieldView($view, $view_path = 'custom-fields::field_print', $custom_field_values, $custom_field, $push_location = null)
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

        // // Set Invoice
        //$compact[] = 'invoice';

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

            $table_name_view = 'custom-fields::invoices.item_th_print';
            $table_item_view = 'custom-fields::invoices.item_td';

            $view->getFactory()->startPush($table_name, view($table_name_view, compact($compact)));

            $view->getFactory()->startPush($table_item, view($table_item_view, compact($compact)));
        }
    }
}

<?php

namespace Modules\CustomFields\Http\ViewComposers;

use Illuminate\View\View;
use Modules\CustomFields\Models\Field;
use Modules\CustomFields\Models\FieldValue;
use Modules\CustomFields\Models\Location;

class Items
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

        $location = Location::where('code', 'common.items')->first();

        $custom_fields = Field::enabled()->orderBy('name')->byLocation($location->id)->get();

        if ($view->getName() == 'common.items.edit') {
            $item = $view->getData()['item'];

            $custom_field_values = FieldValue::record($item->id, get_class($item))->get();
        }

        if ($custom_fields) {
            foreach ($custom_fields as $custom_field) {
                $value = null;

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

                        if (!empty($value)) {
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

                // Push to a stack
                $view->getFactory()->startPush($custom_field->fieldLocation->sort_order, view('custom-fields::field', compact($compact)));
            }
        }
    }
}

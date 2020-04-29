<?php

namespace Modules\CustomFields\Observers\Common;

use App\Abstracts\Observer;
use App\Models\Common\Contact as Model;
use Modules\CustomFields\Models\FieldValue;
use Modules\CustomFields\Traits\CustomFields;

class Contact extends Observer
{
    use CustomFields;

    /**
     * Listen to the created event.
     *
     * @param  Model $contact
     *
     * @return void
     */
    public function created(Model $contact)
    {
        $documentType = $contact->type == 'vendor' ? 'purchases.vendors' : 'sales.customers';
        $custom_fields = $this->getFieldsByLocation($documentType);
        $request = request();

        foreach ($custom_fields as $custom_field) {
            $value = null;

            if (isset($request[$custom_field->code])) {
                $value = $request[$custom_field->code];
            }

            $custom_field_value = FieldValue::create([
                'company_id' => session('company_id'),
                'field_id' => $custom_field->id,
                'type_id' => $custom_field->type_id,
                'type' => $custom_field->type->type,
                'location_id' => $custom_field->locations,
                'model_id' => $contact->id,
                'model_type' => get_class($contact),
                'value' => $value,
            ]);
        }
    }

    public function saved(Model $contact)
    {
        $method = request()->getMethod();

        if ($method == 'PATCH') {
            $this->updated($contact);
        }
    }

    /**
     * Listen to the created event.
     *
     * @param  Model $contact
     *
     * @return void
     */
    public function updated(Model $contact)
    {
        $skips = [];
        $documentType = $contact->type == 'vendor' ? 'purchases.vendors' : 'sales.customers';
        $custom_fields = $this->getFieldsByLocation($documentType);
        $custom_field_values = FieldValue::record($contact->id, get_class($contact))->get();

        $request = request();

        if ($custom_field_values) {
            foreach ($custom_field_values as $custom_field_value) {
                $custom_field = $this->getFieldById($custom_field_value->field_id);

                if (empty($custom_field)) {
                    continue;
                }

                $value = null;

                if (isset($request[$custom_field->code])) {
                    $value = $request[$custom_field->code];
                }

                $custom_field_value->update([
                    'company_id' => session('company_id'),
                    'field_id' => $custom_field->id,
                    'type_id' => $custom_field->type_id,
                    'type' => $custom_field->type->type,
                    'location_id' => $custom_field->locations,
                    'model_id' => $contact->id,
                    'model_type' => get_class($contact),
                    'value' => $value,
                ]);

                $skips[] = $custom_field->id;
            }
        }

        if ($custom_fields) {
            foreach ($custom_fields as $custom_field) {
                if (in_array($custom_field->id, $skips)) {
                    continue;
                }

                $value = null;

                if (isset($request[$custom_field->code])) {
                    $value = $request[$custom_field->code];
                }

                $custom_field_value = FieldValue::create([
                    'company_id' => session('company_id'),
                    'field_id' => $custom_field->id,
                    'type_id' => $custom_field->type_id,
                    'type' => $custom_field->type->type,
                    'location_id' => $custom_field->locations,
                    'model_id' => $contact->id,
                    'model_type' => get_class($contact),
                    'value' => $value,
                ]);
            }
        }
    }

    /**
     * Listen to the deleted event.
     *
     * @param  Model $contact
     *
     * @return void
     */
    public function deleted(Model $contact)
    {
        FieldValue::record($contact->id, get_class($contact))->delete();
    }
}

<?php

namespace Modules\CustomFields\Observers\Banking;

use App\Abstracts\Observer;
use App\Models\Banking\Transfer as Model;
use Modules\CustomFields\Models\FieldValue;
use Modules\CustomFields\Traits\CustomFields;

class Transfer extends Observer
{
    use CustomFields;

    /**
     * Listen to the created event.
     *
     * @param  Model $transfer
     *
     * @return void
     */
    public function created(Model $transfer)
    {
        $custom_fields = $this->getFieldsByLocation('banking.transfers');

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
                'model_id' => $transfer->id,
                'model_type' => get_class($transfer),
                'value' => $value,
            ]);
        }
    }

    public function saved(Model $transfer)
    {
        $method = request()->getMethod();

        if ($method == 'PATCH') {
            $this->updated($transfer);
        }
    }

    /**
     * Listen to the created event.
     *
     * @param  Model $transfer
     *
     * @return void
     */
    public function updated(Model $transfer)
    {
        $skips = [];
        $custom_fields = $this->getFieldsByLocation('banking.transfers');
        $custom_field_values = FieldValue::record($transfer->id, get_class($transfer))->get();

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
                    'model_id' => $transfer->id,
                    'model_type' => get_class($transfer),
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
                    'model_id' => $transfer->id,
                    'model_type' => get_class($transfer),
                    'value' => $value,
                ]);
            }
        }
    }

    /**
     * Listen to the deleted event.
     *
     * @param  Model $transfer
     *
     * @return void
     */
    public function deleted(Model $transfer)
    {
        FieldValue::record($transfer->id, get_class($transfer))->delete();
    }
}

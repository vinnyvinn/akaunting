<?php

namespace Modules\CustomFields\Observers\Purchase;

use App\Abstracts\Observer;
use App\Models\Purchase\Bill as Model;
use Modules\CustomFields\Models\FieldValue;
use Modules\CustomFields\Traits\CustomFields;

class Bill extends Observer
{
    use CustomFields;

    /**
     * Listen to the created event.
     *
     * @param  Model $bill
     *
     * @return void
     */
    public function created(Model $bill)
    {
        $custom_fields = $this->getFieldsByLocation('purchases.bills');

        $request = request();

        foreach ($custom_fields as $custom_field) {
            if (strpos($custom_field->fieldLocation->sort_order, 'td') !== false) {
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
                'model_id' => $bill->id,
                'model_type' => get_class($bill),
                'value' => $value,
            ]);
        }
    }

    public function saved(Model $bill)
    {
        $method = request()->getMethod();

        if ($method == 'PATCH') {
            $this->updated($bill);
        }
    }

    /**
     * Listen to the created event.
     *
     * @param  Model $bill
     *
     * @return void
     */
    public function updated(Model $bill)
    {
        $skips = [];
        $custom_fields = $this->getFieldsByLocation('purchases.bills');
        $custom_field_values = FieldValue::record($bill->id, get_class($bill))->get();

        $request = request();

        //Bill mark paid
        if (!isset($request->_token)) {
            return;
        }

        if ($custom_field_values) {
            foreach ($custom_field_values as $custom_field_value) {
                $custom_field = $this->getFieldById($custom_field_value->field_id);

                if (strpos($custom_field->fieldLocation->sort_order, 'td') !== false) {
                    continue;
                }

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
                    'model_id' => $bill->id,
                    'model_type' => get_class($bill),
                    'value' => $value,
                ]);

                $skips[] = $custom_field->id;
            }
        }

        if ($custom_fields) {
            foreach ($custom_fields as $custom_field) {
                if (strpos($custom_field->fieldLocation->sort_order, 'td') !== false) {
                    continue;
                }

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
                    'model_id' => $bill->id,
                    'model_type' => get_class($bill),
                    'value' => $value,
                ]);
            }
        }
    }

    /**
     * Listen to the deleted event.
     *
     * @param  Model $bill
     *
     * @return void
     */
    public function deleted(Model $bill)
    {
        FieldValue::record($bill->id, get_class($bill))->delete();
    }
}

<?php

namespace Modules\CustomFields\Observers\Sale;

use App\Abstracts\Observer;
use App\Models\Sale\InvoiceItem as Model;
use Modules\CustomFields\Models\FieldValue;
use Modules\CustomFields\Traits\CustomFields;

class InvoiceItem extends Observer
{
    use CustomFields;

    static $invoice_item_count = 0;

    /**
     * Listen to the created event.
     *
     * @param  Model $invoice_item
     *
     * @return void
     */
    public function created(Model $invoice_item)
    {
        $custom_fields = $this->getFieldsByLocation('sales.invoices');

        $request = request();

        foreach ($custom_fields as $custom_field) {
            if (strpos($custom_field->fieldLocation->sort_order, 'td') === false) {
                continue;
            }

            $value = null;

            if (isset($request['items'][static::$invoice_item_count][$custom_field->code])) {
                $value = $request['items'][static::$invoice_item_count][$custom_field->code];
            }

            $custom_field_value = FieldValue::create([
                'company_id' => session('company_id'),
                'field_id' => $custom_field->id,
                'type_id' => $custom_field->type_id,
                'type' => $custom_field->type->type,
                'location_id' => $custom_field->locations,
                'model_id' => $invoice_item->id,
                'model_type' => get_class($invoice_item),
                'value' => $value,
            ]);
        }
    }

    public function saved(Model $invoice_item)
    {

        $method = request()->getMethod();

        if ($method == 'PATCH') {
            $this->updated($invoice_item);
        }
    }

    /**
     * Listen to the created event.
     *
     * @param  Model $invoice_item
     *
     * @return void
     */
    public function updated(Model $invoice_item)
    {
        $skips = [];
        $custom_fields = $this->getFieldsByLocation('sales.invoices');
        $custom_field_values = FieldValue::record($invoice_item->id, get_class($invoice_item))->get();

        $request = request();

        //Invoice mark sent/paid
        if (!isset($request->_token)) {
            return;
        }

        if ($custom_field_values) {
            foreach ($custom_field_values as $custom_field_value) {
                $custom_field = $this->getFieldById($custom_field_value->field_id);

                if (strpos($custom_field->fieldLocation->sort_order, 'td') === false) {
                    continue;
                }

                if (empty($custom_field)) {
                    continue;
                }

                $value = null;

                if (isset($request['items'][static::$invoice_item_count][$custom_field->code])) {
                    $value = $request['items'][static::$invoice_item_count][$custom_field->code];
                }

                $custom_field_value->update([
                    'company_id' => session('company_id'),
                    'field_id' => $custom_field->id,
                    'type_id' => $custom_field->type_id,
                    'type' => $custom_field->type->type,
                    'location_id' => $custom_field->locations,
                    'model_id' => $invoice_item->id,
                    'model_type' => get_class($invoice_item),
                    'value' => $value,
                ]);

                $skips[] = $custom_field->id;
            }
        }

        if ($custom_fields) {
            foreach ($custom_fields as $custom_field) {
                if (strpos($custom_field->fieldLocation->sort_order, 'td') === false) {
                    continue;
                }

                if (in_array($custom_field->id, $skips)) {
                    continue;
                }

                $value = null;

                if (isset($request['items'][static::$invoice_item_count][$custom_field->code])) {
                    $value = $request['items'][static::$invoice_item_count][$custom_field->code];
                }

                $custom_field_value = FieldValue::create([
                    'company_id' => session('company_id'),
                    'field_id' => $custom_field->id,
                    'type_id' => $custom_field->type_id,
                    'type' => $custom_field->type->type,
                    'location_id' => $custom_field->locations,
                    'model_id' => $invoice_item->id,
                    'model_type' => get_class($invoice_item),
                    'value' => $value,
                ]);
            }
        }

        static::$invoice_item_count++;
    }

    /**
     * Listen to the deleted event.
     *
     * @param  Model $invoice_item
     *
     * @return void
     */
    public function deleted(Model $invoice_item)
    {
        FieldValue::record($invoice_item->id, get_class($invoice_item))->delete();
    }
}

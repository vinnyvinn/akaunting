<?php

namespace Modules\CustomFields\Http\Requests;

use Modules\CustomFields\Models\Type;
use App\Abstracts\Http\FormRequest;

class Field extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name'        => 'required|string',
            'code'        => 'required|string',
            'type_id'     => 'required|integer',
            'location_id' => 'required|integer',
            'sort'        => 'required|string',
            'order'       => 'required|string',
            'required'    => 'required|boolean',
            'enabled'     => 'integer|boolean',
        ];

        // $type = Type::find(request()->get('type_id'));
        $type = Type::firstWhere('id', request()->get('type_id'));

        if ($type->type == 'select' || $type->type == 'radio' || $type->type == 'checkbox') {
            $rules['values.*'] = 'required|string';
        }

        return $rules;
    }
}

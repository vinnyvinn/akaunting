<?php

namespace Modules\Inventory\Http\Requests;

use App\Abstracts\Http\FormRequest as Request;

class ItemGroup extends Request
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
        // Get company id
        $company_id = $this->request->get('company_id');

        // Check if store or update
        if ($this->getMethod() == 'PATCH') {
            $id = $this->item_group->getAttribute('id');
        } else {
            $id = null;
        }

        return [
            'name' => 'required|string',
            'category_id' => 'required|integer',
            'option.*.name' => 'required|string',
            'enabled' => 'integer|boolean',
        ];
    }

    public function messages()
    {
        return [
            'option.*.name.required' => trans('validation.required', ['attribute' => mb_strtolower(trans('general.name'))]),
        ];
    }
}

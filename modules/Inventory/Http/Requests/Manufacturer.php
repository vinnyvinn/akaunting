<?php

namespace Modules\Inventory\Http\Requests;

use App\Abstracts\Http\FormRequest as Request;

class Manufacturer extends Request
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

        $vendor_id_check = '';

        // Check if store or update
        if ($this->getMethod() == 'PATCH') {
            $id = $this->manufacturer->getAttribute('id');
        } else {
            $id = null;
        }

        if (!empty($this->request->get('create_vendor'))) {
            $vendor_id_check = 'required';
        }

        return [
            'name' => 'required|string',
            'email' => 'email|unique:inventory_warehouses,NULL,' . $id . ',id,company_id,' . $company_id . ',deleted_at,NULL',
            'vendor_id' => $vendor_id_check,
            'enabled' => 'integer|boolean',
        ];
    }
}

<?php

namespace Modules\Inventory\Http\Requests;

use App\Abstracts\Http\FormRequest as Request;
use Illuminate\Validation\Rule;

class Item extends Request
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
            $id = $this->item->getAttribute('id');
        } else {
            $id = null;
        }

        $picture = 'nullable';

        if ($this->request->get('picture', null)) {
            $picture = 'mimes:' . config('filesystems.mimes') . '|between:0,' . config('filesystems.max_size') * 1024;
        }

        // Get company id
        $company_id = $this->request->get('company_id');

        return [
            'name' => 'required|string',
            // 'sku' => 'required_if:track_inventory:boolean,!=,null,unique:items,NULL,' . $id . ',id,company_id,' . $company_id . ',deleted_at,NULL',
            'sku' => 'nullable|string|unique:items,NULL,' . $id . ',id,company_id,' . $company_id . ',deleted_at,NULL',
            'sale_price' => 'required',
            'purchase_price' => 'required',
            'tax_id' => 'nullable|integer',
            'category_id' => 'nullable|integer',
            'enabled' => 'integer|boolean',
            'picture' => $picture,
        ];
    }
}

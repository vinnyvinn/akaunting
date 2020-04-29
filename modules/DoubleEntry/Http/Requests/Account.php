<?php

namespace Modules\DoubleEntry\Http\Requests;

use App\Abstracts\Http\FormRequest;

class Account extends FormRequest
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
            $id = $this->chart_of_account->getAttribute('id');
        } else {
            $id = null;
        }

        return [
            'name' => 'required|string',
            'code' => 'integer|unique:double_entry_accounts,NULL,' . $id . ',id,company_id,' . $company_id . ',deleted_at,NULL',
            'type_id' => 'integer',
        ];
    }
}

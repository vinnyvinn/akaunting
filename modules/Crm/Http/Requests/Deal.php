<?php

namespace Modules\Crm\Http\Requests;

use App\Abstracts\Http\FormRequest;

class Deal extends FormRequest
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
        return [
            'crm_contact_id' => 'required|integer',
            'crm_company_id' => 'required|integer',
            'name' => 'required|string',
            'amount' => 'required',
            'owner_id' => 'required|integer',
            'pipeline_id' => 'required|integer',
            'color' => 'required|string',
            'closed_at' => 'nullable|date_format:Y-m-d',
        ];
    }
}

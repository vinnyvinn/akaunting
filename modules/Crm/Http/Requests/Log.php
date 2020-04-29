<?php

namespace Modules\Crm\Http\Requests;

use App\Abstracts\Http\FormRequest;

class Log extends FormRequest
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
            'date' => 'nullable|date_format:Y-m-d',
            'time' => 'required',
            'log_type' => 'required',
            'user_id' => 'nullable',
            'subject' => 'nullable',
        ];
    }
}

<?php

namespace Modules\Crm\Http\Requests\Imports;

use App\Abstracts\Http\FormRequest;

class Schedule extends FormRequest
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
            'name' => 'required|string',
            'schedule_type' => 'nullable',
            'started_at' => 'nullable',
            'ended_at' => 'nullable',
            'started_time_at' => 'required',
            'ended_time_at' => 'required',
            'user_id' => 'required|integer',
            'description' => 'nullable',
        ];
    }
}

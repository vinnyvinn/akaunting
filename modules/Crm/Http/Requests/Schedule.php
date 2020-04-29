<?php

namespace Modules\Crm\Http\Requests;

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
            'schedule_type' => 'required',
            'started_at' => 'nullable|date_format:Y-m-d',
            'ended_at' => 'nullable|date_format:Y-m-d',
            'started_time_at' => 'required',
            'ended_time_at' => 'required',
            'user_id' => 'required|integer',
            'description' => 'nullable',
        ];
    }
}

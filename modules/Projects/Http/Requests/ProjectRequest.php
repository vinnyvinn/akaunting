<?php

namespace Modules\Projects\Http\Requests;

use App\Abstracts\Http\FormRequest as Request;

class ProjectRequest extends Request
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
            'name'          => 'required|string',
            'customer_id'   => 'required|integer',
            'started_at'    => 'required|date',
        ];
    }
}

<?php

namespace Modules\DoubleEntry\Http\Requests;

use App\Abstracts\Http\FormRequest;

class JournalItem extends FormRequest
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
            /*'item.*.account_id' => 'required|integer',*/
            'item.*.debit' => 'required|double-entry-amount',
            'item.*.credit' => 'required|double-entry-amount',
        ];
    }

    public function messages()
    {
        return [
            'item.*.account_id.required' => trans('validation.required', ['attribute' => mb_strtolower(trans('doubleentry::general.account'))]),
        ];
    }
}

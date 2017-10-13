<?php

namespace Portal\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ItemLeaseCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return TRUE;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'leasee_name'        => 'required',
            'item_name' => 'required',
            'start_date'        => 'required',
            'end_date'   => 'required'
        ];
    }
}

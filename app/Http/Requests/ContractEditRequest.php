<?php

namespace Portal\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContractEditRequest extends FormRequest
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

            'user_id'                     => 'required|exists:users,id',
            'unit_id'                     => 'required|exists:units,id',
            'leaseholder_email'           => 'sometimes|email',
            'leaseholder_mobile'          => 'sometimes',
            'resident_email'              => 'sometimes|email',
            'resident_mobile'             => 'sometimes',
            'unit_occupation_date'        => 'sometimes|date',
            'unit_vacation_date'          => 'sometimes|date',
            'items'                       => 'sometimes'
        ];
    }
}

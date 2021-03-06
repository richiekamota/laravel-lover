<?php

namespace Portal\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContractCreateRequest extends FormRequest
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
            'user_id'              => 'required|exists:users,id',
            'unit_id'              => 'required|exists:units,id',
            'document_id'          => 'sometimes|exists:documents,id',
            'unit_occupation_date' => 'required|date',
            'unit_vacation_date'   => 'required|date',
            'items'                => 'sometimes'
        ];
    }
}

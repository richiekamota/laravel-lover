<?php

namespace Portal\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UnitEditRequest extends FormRequest
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
            'code'        => 'required',
            'location_id' => 'required|exists:locations,id',
            'type_id'     => 'required|exists:unit_types,id',
            'user_id'     => 'sometimes|exists:users,id',
            'contract_id' => 'sometimes|exists:contracts,id',
        ];
    }
}

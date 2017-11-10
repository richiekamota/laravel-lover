<?php

namespace Portal\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContractAmendmentRequest extends FormRequest
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

            //'document_type' => 'required',
            'file' => 'required',
            'contract_id' => 'required',
        ];
    }
}

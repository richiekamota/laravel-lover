<?php

namespace Portal\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplicationStepSixRequest extends FormRequest
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
            'judgements'         => 'required|boolean',
            'judgements_details' => 'required|required_if:judgements,true',
        ];
    }
}

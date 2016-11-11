<?php

namespace Portal\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplicationCreateRequest extends FormRequest
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
            'first_name'      => 'required',
            'last_name'       => 'required',
            'email'           => 'required|email',
            'password'        => 'required|min:6',
            'sa_id_number'    => 'required_unless:passport_number',
            'passport_number' => 'required_unless:sa_id_number',
            'dob'             => 'required|date',
            'nationality'     => 'required',
            'phone_mobile'    => 'sometimes',
            'phone_home'      => 'sometimes',
            'phone_work'      => 'sometimes',
            'current_address' => 'required',
            'marital_status'  => 'required',
            'married_type'    => 'required_if:marital_status,married',
        ];
    }
}

<?php

namespace Portal\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplicationStepTwoRequest extends FormRequest
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
            'current_property_owner' => 'required',
            'rental_time'            => 'required_if:current_property_owner,false|in:12, 24, 36, 48',
            'rental_amount'          => 'required_if:current_property_owner,false|integer',
            'rental_name'            => 'required_if:current_property_owner,false|max:191',
            'rental_phone_home'      => 'required_if:current_property_owner,false|max:191',
            'rental_phone_mobile'    => 'required_if:current_property_owner,false|max:191',
        ];
    }
}

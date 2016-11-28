<?php

namespace Portal\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplicationStepThreeRequest extends FormRequest
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
            'selfemployed'             => 'required|boolean',
            'occupation'               => 'required',
            'current_monthly_expenses' => 'required',
            'employer_company'         => 'required_if:selfemployed,false|max:191',
            'employer_name'            => 'required_if:selfemployed,false|max:191',
            'employer_phone_work'      => 'required_if:selfemployed,false|max:191',
            'employer_email'           => 'required_if:selfemployed,false|max:191',
            'employer_salary'          => 'required_if:selfemployed,false|max:191',
        ];
    }
}

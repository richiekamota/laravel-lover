<?php

namespace Portal\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplicationStepFourRequest extends FormRequest
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
            'resident_first_name'            => 'required|max:191',
            'resident_last_name'             => 'required|max:191',
            'resident_sa_id_number'          => 'required|max:191',
            'resident_passport_number'       => 'required|max:191',
            'resident_dob'                   => 'required|date',
            'resident_nationality'           => 'required|max:191',
            'resident_phone_mobile'          => 'required|max:191',
            'resident_email'                 => 'required|email|max:191',
            'resident_current_address'       => 'required|max:191',
            'resident_landlord'              => 'required|max:191',
            'resident_landlord_phone_work'   => 'required|max:191',
            'resident_landlord_phone_mobile' => 'required|max:191',
            'resident_study_location'        => 'required|max:191',
            'resident_study_year'            => 'required|numeric|max:1',
            'resident_gender'                => 'required|in:Male,Female,Unlisted',
        ];
    }
}

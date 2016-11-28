<?php

namespace Portal\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplicationStepFiveRequest extends FormRequest
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
            'unit_location'        => 'required',
            'unit_type'            => 'required',
            'unit_lease_length'    => 'required',
            'unit_car_parking'     => 'required|boolean',
            'unit_bike_parking'    => 'required|boolean',
            'unit_tv'              => 'required|boolean',
            'unit_storeroom'       => 'required|boolean',
            'unit_occupation_date' => 'required|date',
        ];
    }
}

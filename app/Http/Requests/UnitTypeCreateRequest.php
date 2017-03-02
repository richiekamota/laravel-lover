<?php

namespace Portal\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UnitTypeCreateRequest extends FormRequest
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
            'name'         => 'required',
            'location_id'  => 'required|exists:locations,id',
            'cost'         => 'required|numeric',
            'wifi'         => 'required|boolean',
            'electricity'  => 'required|boolean',
            'dstv'         => 'required|boolean',
            'parking_car'  => 'required|boolean',
            'parking_bike' => 'required|boolean',
            'storeroom'    => 'required|boolean'
        ];
    }
}

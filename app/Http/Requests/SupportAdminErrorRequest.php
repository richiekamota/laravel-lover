<?php

namespace Portal\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SupportAdminErrorRequest extends FormRequest
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
            'user_issue' => 'required|string',
            'location'   => 'required|string'
        ];
    }
}

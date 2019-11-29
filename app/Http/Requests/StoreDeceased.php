<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDeceased extends FormRequest
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
            'first_name' => 'string|required',
            'middle_name' => 'string',
            'last_name' => 'string|required',
            'day_of_birth' => 'required|date_format:Y-m-d',
            'day_of_death' => 'required|date_format:Y-m-d'
        ];
    }


    public function messages()
    {
        return [
            'day_of_birth.required' => 'Day of birth is required!',
            'day_of_death.required' => 'Day of death is required!',
            'last_name.required' => 'Last name is required!',
            'first_name.required' => 'First name is required!'
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMember extends FormRequest
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
           'email' => 'required|string|email|unique:users',
           'day_of_birth' => 'date|date_format:Y-m-d|before:day_of_death'
        ];
    }


    public function messages()
    {
        return [
            'email.required' => 'Email is required!'
        ];
    }
}

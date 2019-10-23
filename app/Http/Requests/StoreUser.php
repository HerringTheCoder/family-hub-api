<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUser extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
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
            'password' => 'required|string|confirmed'
        ];
    }


    public function messages()
    {
        return [
            'email.required' => 'Email is required!',
            'password.required' => 'Password is required!',
            'password.confirmed' => 'Password is not confirm!'
            
        ];
    }
}

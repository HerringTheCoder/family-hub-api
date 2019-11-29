<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewsRequest extends FormRequest
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
            'title' => 'required|string',
            'description' => 'required|string'
         ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Title is required!',
            'description.required' => 'Title is required!',
            'title.string' => 'Title must be a string!',
            'description.string' => 'Description must be a string!'
        ];
    }
}

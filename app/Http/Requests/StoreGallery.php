<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGallery extends FormRequest
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
            'photo_input' => 'mimes:jpeg,jpg,png,gif,svg|max:10000'
         ];
    }

    public function messages()
    {
        return [
            'photo_input.mimes' => 'Photo mime type is incorrect!',
            'photo_input.max' => 'Photo size is too big!'
        ];
    }
}

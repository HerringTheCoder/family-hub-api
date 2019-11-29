<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RelationRequest extends FormRequest
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
            'type' => 'required|string',
            'stream_direction' => 'required'
         ];
    }

    public function messages()
    {
        return [
            'type.required' => 'Type is required!',
            'type.string' => 'Type must be a string!',
            'stream_direction.required' => 'Stream direction is required!'
        ];
    }
}

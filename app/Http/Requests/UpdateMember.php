<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest as LaravelFormRequest;

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
           'first_name' => 'string|required',
           'middle_name' => 'string|nullable',
           'last_name' => 'string|nullable',
           'day_of_birth' => 'date_format:Y-m-d|nullable|before:day_of_death',
           'day_of_death' => 'date_format:Y-m-d|nullable'
        ];
    }


    public function messages()
    {
        return [
            'first_name.string' => 'First name must be a string!',
            'middle_name.string' => 'Middle name must be a string!',
            'last_name.string' => 'Last name must be a string!',
            'day_of_birth.date' => 'Day of birth must be a date!',
            'day_of_death.date' => 'Day of death must be a date!',
            'day_of_birth.before' => 'Day of birth must be before day of birth!'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        throw new HttpResponseException(
            response()->json(['errors' => $errors], JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
        );
    }
}

<?php

namespace App\Http\Requests\User;

use Illuminate\Http\Response;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class StoreUserRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:50'],
            'document_id' => ['required', 'unique:users', 'string', 'digits:11'],
            'email' => ['required', 'unique:users', 'email'],
            'phone' => ['required', 'string', 'regex:/^\([1-9]{2}\) (?:[2-8]|9[1-9])[0-9]{3}\-[0-9]{4}$/'],
            'password' => ['required', 'string'],
        ];
    }

    public function messages()
    {
        return [
            'phone.regex' => 'Invalid phone format, please use: \'(XX) XXXXX-XXXX\'',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = new Response(['error' => $validator->errors()->all()], 422);
        throw new ValidationException($validator, $response);
    }
}

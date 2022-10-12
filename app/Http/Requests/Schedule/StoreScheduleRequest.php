<?php

namespace App\Http\Requests\Schedule;

use App\Rules\AvailableSchedule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class StoreScheduleRequest extends FormRequest
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
            'vet_id' => ['required', 'exists:vets,id', 'numeric'],
            'date' => [
                'required',
                'date',
                'date_format:d-m-Y H:i',
                'after:today',
                new AvailableSchedule($this->request->get('vet_id'))
            ]
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = new Response(['error' => $validator->errors()->all()], 422);
        throw new ValidationException($validator, $response);
    }
}

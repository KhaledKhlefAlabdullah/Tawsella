<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Mockery\Generator\Method;

class TaxiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        if (request()->isMethod('put')) {
            $id = $this->id;
            return [
                'driver_id' => ['required', 'exists:users,id'],
                'car_name' => ['required', 'string'],
                'lamp_number' => ['required', 'string'],
                //  Rule::unique('taxis', 'lamp_number')->ignore($id,'id')],
                'plate_number' => ['required', 'string'],
                //  Rule::unique('taxis', 'plate_number')->ignore($id,'id')],
                'car_detailes' => ['nullable', 'string']
            ];
        }

        return [
            'driver_id' => ['required', 'exists:users,id'],
            'car_name' => ['required', 'string'],
            'lamp_number' => ['required', 'string'],
            'plate_number' => ['required', 'string'],
            'car_detailes' => ['nullable', 'string']
        ];
    }
}

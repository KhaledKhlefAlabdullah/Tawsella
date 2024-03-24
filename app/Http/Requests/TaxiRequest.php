<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
        return [
            'driver_id' => 'required|exists:users,id',
            'car_name' => 'required|string',
            'lamp_number' => 'required|string',
            'plate_number' => 'required|string|unique:taxis,plate_number',
            'car_detailes' => 'nullable|string',
        ];
    }
}


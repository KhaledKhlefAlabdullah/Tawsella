<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaxiMovementRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'customer_id' => auth()->id(),
        ]);
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'driver_id' => ['sometimes', 'required', 'string', 'exists:users,id'],
            'customer_id' => ['sometimes', 'required', 'string', 'exists:users,id'],
            'taxi_id' => ['sometimes', 'required', 'string', 'exists:taxis,id'],
            'movement_type_id' => ['sometimes', 'required', 'string', 'exists:taxi_movement_types,id'],
            'my_address' => ['nullable', 'sometimes', 'string'],
            'destnation_address' => ['nullable', 'sometimes', 'string'],
            'gender' => ['required', 'sometimes', 'string'],
            'start_latitude' => ['sometimes', 'required', 'numeric'],
            'start_longitude' => ['sometimes', 'required', 'numeric'],
            'end_latitude' => ['sometimes', 'required', 'numeric'],
            'end_longitude' => ['sometimes', 'required', 'numeric'],
            'is_completed' => ['sometimes', 'required', 'boolean'],
            'is_canceled' => ['sometimes', 'required', 'boolean'],
        ];
    }
}

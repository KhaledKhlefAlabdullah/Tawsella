<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class OffersRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function prepareForValidation(): void
    {
        $this->merge(['admin_id' => Auth::id()]);
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'admin_id' => ['required', 'string', 'exists:users,id'],
            'movement_type_id' => ['required', 'exists:taxi_movement_types,id'],
            'offer' => ['sometimes', 'string'],
            'value_of_discount' => ['sometimes', 'numeric', 'min:0', 'max:100'],
            'valid_date' => ['sometimes', 'date', 'after:now'],
            'description' => ['sometimes', 'string']
        ];
    }

    public function messages(): array
    {
        return [
            'admin_id.required' => 'The admin ID field is required.',
            'admin_id.string' => 'The admin ID must be a valid string.',
            'admin_id.exists' => 'The selected admin ID does not exist in the users table.',

            'movement_type_id.required' => 'The movement type field is required.',
            'movement_type_id.exists' => 'The selected movement type ID does not exist in the taxi movement types table.',

            'offer.sometimes' => 'The offer field is optional.',
            'offer.string' => 'The offer must be a valid string.',

            'value_of_discount.sometimes' => 'The discount value is optional.',
            'value_of_discount.numeric' => 'The discount value must be a valid number.',
            'value_of_discount.min' => 'The discount value must be at least 0.',
            'value_of_discount.max' => 'The discount value must not exceed 100.',

            'valid_date.sometimes' => 'The valid date field is optional.',
            'valid_date.date' => 'The valid date must be a valid date format.',
            'valid_date.after' => 'The valid date must be a future date.',

            'description.sometimes' => 'The description field is optional.',
            'description.string' => 'The description must be a valid string.',
        ];
    }
}

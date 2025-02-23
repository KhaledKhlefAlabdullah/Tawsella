<?php

namespace App\Http\Requests\TaxiMovements;

use Illuminate\Foundation\Http\FormRequest;

class MarkMovementAsCompletedRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule','array<mixed>','string>
     */
    public function rules(): array
    {
        return [
            'distance' => ['sometimes','numeric'],
            'end_latitude' => ['required','numeric'],
            'end_longitude' => ['required','numeric'],
            'notes' => ['sometimes','string'],
            'additional_amount' => ['sometimes','numeric'],
            'reason' => ['sometimes','string'],
        ];
    }
}

<?php

namespace App\Http\Requests\Movements;

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
            'way' => ['sometimes','numeric'],
            'end_lat' => ['required','numeric'],
            'end_lon' => ['required','numeric']
        ];
    }
}

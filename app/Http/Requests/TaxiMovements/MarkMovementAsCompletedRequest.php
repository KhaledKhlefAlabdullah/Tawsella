<?php

namespace App\Http\Requests\TaxiMovements;

use App\Enums\PaymentTypesEnum;
use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;

class MarkMovementAsCompletedRequest extends BaseRequest
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
            'notes' => ['nullable','string'],
            'additional_amount' => ['sometimes','numeric'],
            'reason' => ['nullable','string'],
            'coin' => ['sometimes', Rule::in(PaymentTypesEnum::getKeys())],
        ];
    }
}

<?php

namespace App\Http\Requests\TaxiMovements;

use App\Enums\PaymentTypesEnum;
use Illuminate\Foundation\Http\FormRequest;

class TaxiMovementsTypesRequest extends FormRequest
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
            'type' => ['required'],
            'price' => ['required', 'numeric'],
            'description' => ['nullable'],
            'is_onKM' => ['required', 'boolean'],
            'payment' => ['required', 'in:' . implode(',', PaymentTypesEnum::getValues())],
            'is_general' => ['required', 'boolean'],
        ];
    }
}

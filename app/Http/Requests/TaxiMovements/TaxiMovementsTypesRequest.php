<?php

namespace App\Http\Requests\TaxiMovements;

use App\Enums\PaymentTypesEnum;
use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;

class TaxiMovementsTypesRequest extends BaseRequest
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
            'type' => ['sometimes'],
            'price1' => ['sometimes', 'numeric'],
            'price2' => ['sometimes', 'numeric'],
            'description' => ['nullable'],
            'is_onKM' => ['sometimes', 'boolean'],
            'payment1' => ['sometimes', Rule::in(PaymentTypesEnum::getKeys())],
            'payment2' => ['sometimes', Rule::in(PaymentTypesEnum::getKeys())],
            'is_general' => ['sometimes', 'boolean'],
        ];
    }
}

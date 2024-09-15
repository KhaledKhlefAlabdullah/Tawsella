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
}

<?php

namespace App\Http\Requests\TaxiMovements;

use App\Enums\UserEnums\UserGender;
use App\Http\Requests\BaseRequest;
use App\Models\TaxiMovementType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class TaxiMovementRequest extends BaseRequest
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
            'customer_id' => Auth::id(),
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
            'movement_type_id' => ['sometimes', 'string', 'exists:taxi_movement_types,id'],
            'start_address' => ['nullable', 'sometimes', 'string'],
            'destination_address' => ['nullable', 'sometimes', 'string'],
            'gender_person' => ['required', 'sometimes', 'string', Rule::in(UserGender::getKeys())],
            'start_latitude' => ['sometimes', 'required', 'numeric'],
            'start_longitude' => ['sometimes', 'required', 'numeric'],
            'end_latitude' => ['sometimes', 'required', 'numeric'],
            'end_longitude' => ['sometimes', 'required', 'numeric'],
        ];
    }
}

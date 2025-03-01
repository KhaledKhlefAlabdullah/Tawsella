<?php

namespace App\Http\Requests\Taxis;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;

class TaxiRequest extends BaseRequest
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
        $taxi = $this->route('taxi');
        $uniqueLampNumberRule = Rule::unique('taxis', 'lamp_number');
        $uniquePlateNumberRule = Rule::unique('taxis', 'plate_number');

        if ($taxi) {
            $uniqueLampNumberRule->ignore($taxi->id);
            $uniquePlateNumberRule->ignore($taxi->id);
        }

        return [
            'driver_id' => ['required', 'exists:users,id'],
            'car_name' => ['required', 'string'],
            'lamp_number' => ['required', 'string', $uniqueLampNumberRule],
            'plate_number' => ['required', 'string', $uniquePlateNumberRule],
            'car_details' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'driver_id.required' => 'The driver ID is required.',
            'driver_id.exists' => 'The selected driver does not exist.',
            'car_name.required' => 'The car name is required.',
            'car_name.string' => 'The car name must be a string.',
            'lamp_number.required' => 'The lamp number is required.',
            'lamp_number.string' => 'The lamp number must be a string.',
            'lamp_number.unique' => 'The lamp number has already been taken.',
            'plate_number.required' => 'The plate number is required.',
            'plate_number.string' => 'The plate number must be a string.',
            'plate_number.unique' => 'The plate number has already been taken.',
            'car_details.string' => 'Car details must be a string.',
        ];
    }
}


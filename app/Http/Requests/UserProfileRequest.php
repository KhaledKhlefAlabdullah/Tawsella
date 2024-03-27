<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserProfileRequest extends FormRequest
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
            'name' => ['required', 'sometimes', 'string'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phoneNumber' => ['required', 'sometimes', 'string', 'regex:/^\+?[0-9]{9,20}$/', 'unique:user_profiles,phoneNumber'],
            'carPlatNumber' => ['sometimes', 'string', 'unique:car_table,plat_number'],
            'carLampNumber' => ['sometimes', 'string'],
            'avatar' => ['sometimes', 'image', 'mimes:png,jpg,gif']
        ];
    }
    
}

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
            'name' => ['required','sometimes','string'],
            'email' => ['required', 'string', 'email', 'max:255','unique'],
            'phoneNumber' => ['required','sometimes','string','regex:/^\+?[0-9]{9,20}$/','unique'],
            'carPlatNumber' => ['required','sometimes','string','unique'],
            'carLampNumber' => ['required','sometimes','string'],
            'avatar' => ['required','sometimes','image','mimes:png,jpg,gif']
        ];
    }
}

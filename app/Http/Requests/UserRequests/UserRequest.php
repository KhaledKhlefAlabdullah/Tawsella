<?php

namespace App\Http\Requests\UserRequests;

use App\Enums\UserEnums\UserGender;
use App\Models\User;
use App\Models\UserProfile;
use App\Rules\PhoneNumber;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserRequest extends FormRequest
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
        $user = Auth::user();
        return [
            'device_token' => ['nullable','string'],
            'name' => ['sometimes', 'string', 'max:255'],
            'email' => ['sometimes', 'string', 'lowercase', 'email', 'max:255', is_null($user) ? 'unique:' . User::class : Rule::unique('users')->ignore($user->id)],
            'phone_number' => ['sometimes', 'nullable', 'string', new PhoneNumber, is_null($user) ? 'unique:' . UserProfile::class : Rule::unique('user_profiles')->ignore($user->profile?->id)],
            'avatar' => ['sometimes','mimes:jpeg,jpg,png', 'max:10048'],
            'gender' => ['sometimes', 'string', Rule::in(UserGender::getKeys())],
            'address' => ['sometimes', 'string', 'max:255'],
            'birthdate' => ['sometimes', 'date', 'date_format:Y-m-d'],
            'password' => ['sometimes', Password::defaults()],
        ];
    }

    /**
     * Get custom error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'The name field is required.',
            'name.string' => 'The name field must be a string.',
            'name.max' => 'The name field must not exceed 255 characters.',
            'name.min' => 'The name field must be at least 3 characters.',
            'email.required' => 'The email field is required.',
            'email.string' => 'The email field must be a string.',
            'email.email' => 'The email is invalid.',
            'email.max' => 'The email field must not exceed 255 characters.',
            'email.unique' => 'The email has already been taken.',
            'phone_number.unique' => 'The phone number has already been taken.',
            'phone_number.required' => 'The phone number field is required.',
            'phone_number.string' => 'The phone number field must be a string.',
            'phone_number.regex' => 'The phone number is invalid.',
            'password.required' => 'The password field is required.',
            'password.confirmed' => 'The password confirmation does not match.',
            'gender.required' => 'The gender field is required.'
        ];
    }
}

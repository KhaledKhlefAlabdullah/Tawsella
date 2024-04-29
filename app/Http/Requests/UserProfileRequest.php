<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

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
        // تلقا بشقك
        // غير قابل للتعديل

        $id = $this->id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['sometimes', 'string', 'email', 'max:255'],
            'password' => ['sometimes', 'required', 'confirmed', Rules\Password::defaults()],
            'phoneNumber' => ['required', 'string', 'regex:/^(00|\+)[0-9]{9,20}$/'],
            'avatar' => ['sometimes', 'nullable', 'mimes:png,jpg,jpeg', 'max:10024'], // Example: max file size of 10MB
        ];
    }
}

<?php

namespace App\Http\Requests\Auth;

use App\Enums\UserEnums\UserGender;
use App\Enums\UserEnums\UserType;
use App\Models\User;
use App\Models\UserProfile;
use App\Rules\PhoneNumber;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
        $id = $this->route('id');
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'email' => ['sometimes', 'string', 'lowercase', 'email', 'max:255', is_null($id) ? 'unique:' . User::class : Rule::unique('users')->ignore($id)],
            'phone_number' => ['sometimes', 'nullable', 'string', new PhoneNumber, is_null($id) ? 'unique:' . UserProfile::class : Rule::unique('user_profiles')->ignore($id)],
            'user_type' => ['sometimes', 'string', Rule::in(UserType::getUsersTypes())],
            'gender' => ['sometimes', 'string', Rule::in(UserGender::getValues())],
            'password' => ['sometimes',],
            'active' => ['sometimes']
        ];
    }
}

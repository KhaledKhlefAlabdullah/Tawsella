<?php

namespace App\Http\Requests\UserRequests;

use App\Enums\UserEnums\UserGender;
use App\Models\User;
use App\Models\UserProfile;
use App\Rules\PhoneNumber;
use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateDriverRequest extends BaseRequest
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
        $user = $this->route('driver');
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'email' => ['sometimes', 'string', 'lowercase', 'email', 'max:255', is_null($user) ? 'unique:' . User::class : Rule::unique('users')->ignore($user->id)],
            'phone_number' => ['sometimes', 'nullable', 'string', new PhoneNumber, is_null($user) ? 'unique:' . UserProfile::class : Rule::unique('user_profiles')->ignore($user->profile?->id)],
            'gender' => ['sometimes', 'string', Rule::in(UserGender::getKeys())],
            'password' => ['sometimes', Password::defaults()],
            'birthdate' => ['sometimes', 'date', 'date_format:Y-m-d'],
        ];
    }
}

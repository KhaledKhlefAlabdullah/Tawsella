<?php

namespace App\Http\Requests\Auth;

use App\Enums\UserGender;
use App\Enums\UserType;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

use function PHPUnit\Framework\isNull;

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
            'phone_number' => ['sometimes', 'nullable', 'string', 'regex:/^(00|\+)[0-9]{9,20}$/'],
            'user_type' => ['sometimes', 'string', Rule::in(UserType::values())],
            'gender' => ['sometimes', 'string', Rule::in(UserGender::values())],
            'password' => ['sometimes', ],
            'active' => ['sometimes']
        ];
    }
}

<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Models\UserProfile;
use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
            'phoneNumber' => ['required', 'string', 'regex:/^(00|\+)[0-9]{9,20}$/', 'max:255', $this->user()->user_profile ? Rule::unique(UserProfile::class)->ignore($this->user()->user_profile->id): 'nullable']
        ];
    }
}

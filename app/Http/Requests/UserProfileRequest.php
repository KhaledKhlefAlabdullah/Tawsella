<?php

namespace App\Http\Requests;

use App\Models\Taxi;
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

        $id = $this->route('id');
        
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($id)],
            'phoneNumber' => ['required', 'string', 'regex:/^\+[0-9]{9,20}$/', Rule::unique('user_profiles')->ignore($id, 'user_id')],
            'avatar' => ['sometimes', 'image', 'max:10024'], // Example: max file size of 10MB
            'plate_number' => ['sometimes','required', 'string', 'max:255', Rule::unique('taxis')->ignore(Taxi::where('driver_id', $id)->pluck('id')->first())],
            'lamp_number' => ['sometimes','required', 'string', 'max:255', Rule::unique('taxis')->ignore(Taxi::where('driver_id', $id)->pluck('id')->first())],
        ];
        
    }
}

<?php

namespace App\Http\Requests;

use App\Rules\PhoneNumber;
use App\Http\Requests\BaseRequest;
use Illuminate\Support\Facades\Auth;

class ContactUsMessagesRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'admin_id' => getAdminId(),
        ]);

        $user = Auth::user();
        if ($user) {
            $this->merge([
                'sender_name' => $user->profile?->name,
                'email' => $user->email,
                'phone_number' => $user->profile?->phone_number,
                'is_registeredInApp' => true
            ]);
        }else{
            $this->merge([
                'is_registeredInApp' => false
            ]);
        }
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'admin_id' => ['string', 'required', 'exists:users,id'],
            'is_registeredInApp' => ['boolean','required'],
            'sender_name' => ['string', 'required', ''],
            'email' => ['string', 'required', 'email'],
            'phone_number' => ['string','nullable', new PhoneNumber],
            'message' => ['string', 'required']
        ];
    }
}

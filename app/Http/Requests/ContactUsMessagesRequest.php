<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactUsMessagesRequest extends FormRequest
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
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'admin_id' => ['string','required','exists:users,id'],
            'description' => ['string','required',''],
            'email' => ['string','required','email'],
            'phone_number' => ['string','sometimes','required','regex:/^(00|\+)[0-9]{9,20}$/
']
        ];
    }
}

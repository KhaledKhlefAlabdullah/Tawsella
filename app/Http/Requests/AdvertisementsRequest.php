<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdvertisementsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Data preparation before validation.
     */
    protected function prepareForValidation(): void
    {
        // Set user_id based on the current user making the request
        $this->merge(['admin_id' => getAdminId()]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'admin_id' => ['sometimes','string',],
            'name' => ['sometimes','string',],
            'description' => ['sometimes','string',],
            'image' => ['nullable','mimes:png,jpg,jpeg','max:10024'],
            'logo' => ['nullable','mimes:png,jpg,jpeg','max:10024'],
            'validity_date' => ['sometimes','dateTime','after:today'],
        ];
    }

    public function messages(): array{
        return [
          'image.mimes' => 'The image must be a file of type: png, jpg, jpeg',
          'logo.mimes' => 'The image must be a file of type: png, jpg, jpeg',
        ];
    }
}



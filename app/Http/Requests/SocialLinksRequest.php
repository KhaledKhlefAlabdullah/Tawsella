<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SocialLinksRequest extends FormRequest
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
            'admin_id' => ['uuid', 'exists:users,id'],
            'title' => ['required', 'string'],
            'link' => ['required', 'string'],
            'icon' => ['nullable', 'image', 'mimes:jpg,png,jpeg','max:10024'],
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ServicesRequest extends FormRequest
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
        $this->merge(['admin_id' => Auth::id()]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'admin_id' => ['required','string',],
            'name' => ['required','string',],
            'description' => ['required','string',],
            'image' => ['sometimes','image','mimes:png,jpg,jpeg','max:1024'],
            'logo' => ['sometimes','image','mimes:png,jpg,jpeg','max:1024']
        ];
    }
}

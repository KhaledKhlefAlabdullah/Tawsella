<?php

namespace App\Http\Requests\Taxis;

use App\Http\Requests\BaseRequest;

class GetTaxiLocationRequest extends BaseRequest
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
        return [
            'lat' => ['numeric','required'],
            'long' => ['numeric','required']
        ];
    }
}

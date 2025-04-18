<?php

namespace App\Http\Requests;

use App\Enums\UserEnums\DriverState;
use App\Http\Requests\BaseRequest;



class DriverStateRequest extends BaseRequest
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
            'state' => ['required', 'in:' . implode(',', [DriverState::Ready, DriverState::InBreak])],
        ];
    }
}

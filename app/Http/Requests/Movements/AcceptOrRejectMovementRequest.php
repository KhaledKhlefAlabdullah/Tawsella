<?php

namespace App\Http\Requests\Movements;

use App\Enums\MovementRequestStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AcceptOrRejectMovementRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function prepareForValidation(): void{
        $this->merge([
            'driver_id' => getMyId()
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule','array<mixed>','string>
     */
    public function rules(): array
    {
        return [
            'driver_id' => ['required','uuid','exists:users,id'],
            'message' => ['string','sometimes','nullable']
        ];
    }
}

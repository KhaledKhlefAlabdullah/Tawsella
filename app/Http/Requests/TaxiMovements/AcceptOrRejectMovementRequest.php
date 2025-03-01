<?php

namespace App\Http\Requests\TaxiMovements;

use App\Http\Requests\BaseRequest;

class AcceptOrRejectMovementRequest extends BaseRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule','array<mixed>','string>
     */
    public function rules(): array
    {
        return [
            'driver_id' => ['sometimes','uuid','exists:users,id'],
            'message' => ['string','sometimes','nullable']
        ];
    }
}

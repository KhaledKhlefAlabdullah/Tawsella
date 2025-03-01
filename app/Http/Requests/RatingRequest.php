<?php

namespace App\Http\Requests;

use App\Models\TaxiMovement;
use App\Http\Requests\BaseRequest;
use Illuminate\Support\Facades\Auth;

class RatingRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function prepareForValidation(): void
    {
        $latestMovement = TaxiMovement::whereDate('created_at', '<=', now())->latest()->first();

        if (!$latestMovement) {
            // Handle the absence of a valid driver (e.g., throw an exception or set a default driver_id)
            abort(400, 'No valid driver found for the request.');
        }

        $this->merge([
            'customer_id' => Auth::id(),
            'driver_id' => $latestMovement->driver_id,
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
            'customer_id' => ['required','uuid','exists:users,id'],
            'driver_id' => ['required','uuid','exists:users,id'],
            'rating' => ['required','number','between:1,5'],
            'notes' => ['nullable','string']
        ];
    }
}

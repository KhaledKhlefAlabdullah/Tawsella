<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;



class DriverStateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        // يُحضر المعرّف للسائق من جلسة المستخدم أو من أي منطق تطبيق محدد لديك
        $this->merge([
            'driver_id' => auth()->id(),
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
            'driver_id' => ['required', 'exists:users,id'], // تحديد معرّف السائق
            'state' => ['required', 'in:0,1'], // حالة السائق: 0 أو 1
        ];
    }
}

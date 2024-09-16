<?php
namespace App\Http\Requests\UserRequests;

use App\Enums\UserEnums\UserGender;
use App\Models\User;
use App\Models\UserProfile;
use App\Rules\PhoneNumber;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
        $id = $this->route('id');
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'email' => ['sometimes', 'string', 'lowercase', 'email', 'max:255', is_null($id) ? 'unique:' . User::class : Rule::unique('users')->ignore($id)],
            'phone_number' => ['sometimes', 'nullable', 'string', new PhoneNumber, is_null($id) ? 'unique:' . UserProfile::class : Rule::unique('user_profiles')->ignore($id)],
            'gender' => ['sometimes', 'string', Rule::in(UserGender::getKeys())],
            'password' => ['sometimes',],
        ];
    }

    /**
     * Get custom error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'حقل الاسم مطلوب.',
            'name.string' => 'حقل الاسم يجب أن يكون نصًا.',
            'name.max' => 'حقل الاسم يجب ألا يتجاوز 255 حرفًا.',
            'name.min' => 'حقل الاسم يجب أن لا يقل عن 3 محارف.',
            'email.required' => 'حقل البريد الإلكتروني مطلوب.',
            'email.string' => 'حقل البريد الإلكتروني يجب أن يكون نصًا.',
            'email.email' => 'البريد الإلكتروني غير صحيح.',
            'email.max' => 'حقل البريد الإلكتروني يجب ألا يتجاوز 255 حرفًا.',
            'email.unique' => 'البريد الإلكتروني موجود من قبل في قاعدة البيانات.',
            'phone_number.required' => 'حقل رقم الهاتف مطلوب.',
            'phone_number.string' => 'حقل رقم الهاتف يجب أن يكون نصًا.',
            'phone_number.regex' => 'رقم الهاتف غير صحيح.',
            'password.required' => 'حقل كلمة المرور مطلوب.',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق.',
            'gender.required' => 'حقل الجنس مطلوب'
        ];
    }
}

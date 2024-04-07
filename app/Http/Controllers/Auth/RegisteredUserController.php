<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserProfile;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

use function PHPUnit\Framework\isNull;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, string $user_type = 'customer')
    {
        try {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
                'phone_number' => ['required', 'string', 'regex:/^\+[0-9]{9,20}$/'],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ], [
                'name.required' => 'حقل الاسم مطلوب.',
                'name.string' => 'حقل الاسم يجب أن يكون نصًا.',
                'name.max' => 'حقل الاسم يجب ألا يتجاوز 255 حرفًا.',
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
            ]);;

            $user = User::create([
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'user_type' => $user_type,
                'driver_state' => $user_type == 'driver' ? 'ready' : null
            ]);

            UserProfile::create([
                'user_id' => $user->id,
                'name' => $request->input('name'),
                'gender' => $user_type == 'driver' ? $request->gender : null,
                'phoneNumber' => $request->input('phone_number'),
            ]);

            if ($request->wantsJson()) {

                $token = createToken($user, 'register-token');

                return api_response(data: ['token' => $token, 'user_id' => $user->id], message: 'register-success');
            }

            Session::flash('success', 'تم إنشاء حساب السائق بنجاح.');

            // Redirect back or to any other page
            return redirect()->back();
        } catch (Exception $e) {
            if (request()->wantsJson()) {
                return api_response(message: $e->getMessage(), code: 500);
            }
            return redirect()->back()->withErrors('هنالك خطأ في جلب البيانات الرجاء المحاولة مرة أخرى :' . $e->getMessage())->withInput();
        }
    }


    /**
     * Admin register to create driver account
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function admin_store(Request $request)
    {
        $request->validate([
            'gender' => ['required', 'string', 'in:male,female']
        ]);
        //dd($request);
        return $this->store($request, 'driver');
    }
}

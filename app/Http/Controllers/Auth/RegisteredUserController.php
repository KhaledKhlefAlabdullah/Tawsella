<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserEnums\DriverState;
use App\Enums\UserEnums\UserGender;
use App\Enums\UserEnums\UserType;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequests\UserRequest;
use App\Models\User;
use App\Models\UserProfile;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

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
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Validation\ValidationException|JsonResponse if request want json return json response if not redirect to main page
     * @returns
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(UserRequest $request, string $user_type = UserType::Customer)
    {
        try {
            DB::beginTransaction();
            $validatedData = $request->validated();

            $user = User::create([
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'user_type' => $user_type,
            ]);

            UserProfile::create([
                'user_id' => $user->id,
                'name' => $validatedData['name'],
                'gender' => UserGender::getValue($validatedData['gender']),
                'phone_number' => $validatedData['phone_number'],
            ]);

            if ($request->wantsJson()) {
                $user->assignRole(UserType::Customer()->key);

                $user->sendEmailVerificationNotification(true);

                $token = createUserToken($user, 'register-token');
                DB::commit();
                return api_response(data: ['token' => $token, 'user_id' => $user->id], message: 'register-success');
            }

            $user->assignRole(UserType::TaxiDriver()->key);

            $user->mail_code_verified_at = now();
            $user->save();
            Session::flash('success', 'تم إنشاء حساب السائق بنجاح.');
            DB::commit();

            // Redirect back or to any other page
            return redirect()->back();

        } catch (Exception $e) {
            DB::rollBack();
            if (request()->wantsJson()) {
                return api_response(message: $e->getMessage(), code: 500);
            }
            return redirect()->back()->withErrors('هنالك خطأ في جلب البيانات الرجاء المحاولة مرة أخرى :' . $e->getMessage())->withInput();
        }
    }


    /**
     * Admin register to create driver account
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Validation\ValidationException|JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function create_driver(UserRequest $request)
    {
        return $this->store($request, UserType::TaxiDriver);
    }
}

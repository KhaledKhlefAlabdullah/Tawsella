<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserEnums\UserType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    /**
     * Login to the app
     * @param LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(LoginRequest $request)
    {
        try {

            $request->authenticate();

//            // Delete all existing tokens for the authenticated user
            // Get user details
            $user = $request->user();
            $user->device_token = $request->input('device_token');
            $user->save();
            $taxi = $user->taxi;

            if ($user->hasRole(\App\Enums\UserEnums\UserType::TaxiDriver()->key) && !$taxi) {
                return api_response(message: 'لا يمكنك تسجيل الدخول بهذا الحساب لأنه غير مربوط بتكسي', code: 403);
            }

            if ($user->hasRole(UserType::Customer()->key) || $user->hasRole(UserType::TaxiDriver()->key)) {
                $request->user()->tokens()->delete();
            }

            $token = createUserToken($user, 'login-token');

            return api_response(data: ['token' => $token, 'user' => $user, 'profile' => $user->profile, 'mail_code_verified_at' => $user->mail_code_verified_at], message: 'تم تسجيل الدخول بنجاح');

        } catch (AuthenticationException $e) {
            // Catch AuthenticationException and return an unauthorized response
            return api_response(message: 'معلومات تسجيل الدخول غير صحيحة', code: 401, errors: [[$e->getMessage()], 'وصول غير مصرح به']);
        } catch (ValidationException $e) {
            // Catch ValidationException and return a validation error response
            return api_response(message: 'خطأ في التحقق من صخة البيانات', code: 422, errors: [$e->errors()]);
        } catch (Exception $e) {
            return api_response(message: 'خطأ في تسجيل الدخول', code: 500, errors: [$e->getMessage()]);
        }
    }

    /**
     * Logout function
     * @param Request $request
     * @return \Illuminate\Foundation\Application|\Illuminate\Http\JsonResponse|RedirectResponse|\Illuminate\Routing\Redirector|never
     */
    public function destroy(Request $request)
    {
        try {

            $user = $request->user();
            if ($user->hasRole(UserType::Customer()->key) || $user->hasRole(UserType::TaxiDriver()->key)) {
                $user->currentAccessToken()->delete();
            }

            return api_response(message: 'تم تسجيل الخروج بنجاح');

        } catch (\Exception $e) {
            // Handle any exceptions that might occur during logout
            return api_response(message: 'هناك خطأ في تسجيل الخروج', errors: [$e->getMessage()]);
        }
    }
}

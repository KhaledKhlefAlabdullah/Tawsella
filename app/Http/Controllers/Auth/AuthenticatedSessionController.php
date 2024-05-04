<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException as ValidationValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        try {

            $request->authenticate();


            // Delete all existing tokens for the authenticated user
            $request->user()->tokens()->delete();

            // Get user details
            $user = $request->user();

            // Create a new token for the user
            $token = createUserToken($user, 'login-token');

            return api_response(data: ['token' => $token, 'user' => $user], message: 'نجح تسجيل الدخول');
        } catch (AuthenticationException $e) {
            return api_response(errors: [$e->getMessage(), 'دخول غير مرخص'], message: 'بيانات الاعتماد غير صالحة', code: 401);
        } catch (ValidationValidationException $e) {
            return api_response(errors: [$e->errors()], message: 'خطئ في التحقق', code: 422);
        } catch (Exception $e) {
            return api_response(errors: [$e->getMessage()], message: 'خطأ في تسجيل الدخول', code: 500);
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request)
    {
        try {

            $request->user()->currentAccessToken()->delete();

            return api_response(message: 'تم تسجيل الخروج بنجاح');
        } catch (Exception $e) {
            return api_response(errors: [$e->getMessage()], message: 'هناك خطأ في تسجيل الخروج حاول مرة أخرى');
        }
    }
}

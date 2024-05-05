<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException as ValidationValidationException;
use Illuminate\View\View;
use Illuminate\Validation\Rules;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     * This method returns the view for the login page.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     * This method handles the login process and creates a new token for the authenticated user.
     */
    public function store(LoginRequest $request)
    {
        try {
            // Authenticate the user
            $request->authenticate();

            // Delete all existing tokens for the authenticated user
            $request->user()->tokens()->delete();

            // Get user details
            $user = $request->user();

            // Create a new token for the user
            $token = createUserToken($user, 'login-token');

            // Return the API response with the token and user data
            return api_response(data: ['token' => $token, 'user' => $user], message: 'نجح تسجيل الدخول');
        } catch (AuthenticationException $e) {
            // Return an API response with authentication errors
            return api_response(errors: [$e->getMessage(), 'دخول غير مرخص'], message: 'بيانات الاعتماد غير صالحة', code: 401);
        } catch (ValidationValidationException $e) {
            // Return an API response with validation errors
            return api_response(errors: [$e->errors()], message: 'خطئ في التحقق', code: 422);
        } catch (Exception $e) {
            // Return a generic API response for login errors
            return api_response(errors: [$e->getMessage()], message: 'خطأ في تسجيل الدخول', code: 500);
        }
    }

    /**
     * Destroy an authenticated session.
     * This method handles the logout process by deleting the current access token.
     */
    public function destroy(Request $request)
    {
        try {
            // Delete the current access token
            $request->user()->currentAccessToken()->delete();

            // Return an API response indicating successful logout
            return api_response(message: 'تم تسجيل الخروج بنجاح');
        } catch (Exception $e) {
            // Return an API response with an error message for failed logout
            return api_response(errors: [$e->getMessage()], message: 'هناك خطأ في تسجيل الخروج حاول مرة أخرى');
        }
    }

    /**
     * Change password
     * This method handles the process of changing the user's password.
     */
    public function change_password(Request $request)
    {
        try {
            // Validate the input data
            $request->validate([
                'current_password' => 'required',
                'new_password' =>  ['required', 'confirmed', Rules\Password::defaults()]
            ]);

            // Get the authenticated user
            $user = Auth::user();

            // Hash the new password
            $new_password = Hash::make($request->input('new_password'));

            // Check if the new password is the same as the old password
            if ($user->password == $new_password) {
                // Return an API response with an error message
                return response()->json([
                    'message' => __('Your new password it same old password')
                ], 400);
            }

            // Update the user's password
            $user->password = $new_password;
            $user->save;

            // Return an API response indicating successful password change
            return api_response(message: 'تم تغيير كلمة المرور بنجاح');
        } catch (Exception $e) {
            // Return an API response with an error message for failed password change
            return api_response(errors: $e->getMessage(), message: 'هناك مشكلة في تغيير كلمة المرور الخاصة بك', code: 500);
        }
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\RedirectResponse;
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

            // Return a JSON response with the token and user details
            if ($request->wantsJson()) {

                // Delete all existing tokens for the authenticated user
                $request->user()->tokens()->delete();

                // Get user details
                $user = $request->user();

                // Create a new token for the user
                $token = createUserToken($user, 'login-token');

                return api_response(data: ['token' => $token, 'user' => $user], message: 'نجح تسجيل الدخول');
            }

            $request->session()->regenerate();

            return redirect()->intended(RouteServiceProvider::HOME);
        } catch (AuthenticationException $e) {
            // Catch AuthenticationException and return an unauthorized response
            if ($request->wantsJson())
                return api_response(errors: [$e->getMessage(), 'دخول غير مرخص'], message: 'بيانات الاعتماد غير صالحة', code: 401);
            return redirect()->back()->withErrors('هنالك خطأ في جلب البيانات الرجاء المحاولة مرة أخرى.\n errors:'.$e->getMessage())->withInput();
        } catch (ValidationValidationException $e) {
            // Catch ValidationException and return a validation error response
            if ($request->wantsJson())
                return api_response(errors: [$e->errors()], message: 'خطئ في التحقق', code: 422);
            return redirect()->back()->withErrors('هنالك خطأ في جلب البيانات الرجاء المحاولة مرة أخرى.\n errors:'.$e->getMessage())->withInput();
        } catch (Exception $e) {
            if ($request->wantsJson())
                return api_response(errors: [$e->getMessage()], message: 'خطأ في تسجيل الدخول', code: 500);
            return redirect()->back()->withErrors('هنالك خطأ في جلب البيانات الرجاء المحاولة مرة أخرى.\n errors:'.$e->getMessage())->withInput();
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request)
    {
        try {
            if (request()->wantsJson()) {
                $request->user()->currentAccessToken()->delete();

                return api_response(message: 'تسجيل الخروج بنجاح');
            }

            Auth::guard('web')->logout();

            $request->session()->invalidate();

            $request->session()->regenerateToken();

            return redirect('/');
        } catch (\Exception $e) {
            if (request()->wantsJson()) {

                // Handle any exceptions that might occur during logout
                return api_response(errors: [$e->getMessage()], message: 'هناك خطأ في تسجيل الخروج حاول مرة أخرى');
            }
        }

        return abort(500, 'هناك خطأ في تسجيل الخروج حاول مرة أخرى');
    }
}

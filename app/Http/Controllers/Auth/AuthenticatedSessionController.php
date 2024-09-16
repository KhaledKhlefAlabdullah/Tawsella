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
use Illuminate\Validation\ValidationException;
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
     * Login to the app
     * @param LoginRequest $request
     * @return \Illuminate\Http\JsonResponse|RedirectResponse
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

                return api_response(data: ['token' => $token, 'user' => $user, 'mail_code_verified_at' => $user->mail_code_verified_at], message: 'Successfully logged in');
            }

            $request->session()->regenerate();

            return redirect()->intended(RouteServiceProvider::HOME);
        } catch (AuthenticationException $e) {
            // Catch AuthenticationException and return an unauthorized response
            if ($request->wantsJson())
                return api_response(errors: [$e->getMessage(), 'Unauthorized access'], message: 'Invalid credentials', code: 401);
            return redirect()->back()->withErrors('There was an error retrieving the data, please try again. \n errors:'.$e->getMessage())->withInput();
        } catch (ValidationException $e) {
            // Catch ValidationException and return a validation error response
            if ($request->wantsJson())
                return api_response(errors: [$e->errors()], message: 'Validation error', code: 422);
            return redirect()->back()->withErrors('There was an error retrieving the data, please try again. \n errors:'.$e->getMessage())->withInput();
        } catch (Exception $e) {
            if ($request->wantsJson())
                return api_response(errors: [$e->getMessage()], message: 'Login error', code: 500);
            return redirect()->back()->withErrors('There was an error retrieving the data, please try again. \n errors:'.$e->getMessage())->withInput();
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
            if (request()->wantsJson()) {
                $request->user()->currentAccessToken()->delete();

                return api_response(message: 'Successfully logged out');
            }

            Auth::guard('web')->logout();

            $request->session()->invalidate();

            $request->session()->regenerateToken();

            return redirect('/');
        } catch (\Exception $e) {
            if (request()->wantsJson()) {

                // Handle any exceptions that might occur during logout
                return api_response(errors: [$e->getMessage()], message: 'Logged out error');
            }
        }

        return abort(500, 'Logged out error');
    }
}

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

            // Delete all existing tokens for the authenticated user
            $request->user()->tokens()->delete();

            // Get user details
            $user = $request->user();

            // Create a new token for the user
            $token = createUserToken($user, 'login-token');

            return api_response(data: ['token' => $token, 'user' => $user, 'mail_code_verified_at' => $user->mail_code_verified_at], message: 'Successfully logged in');

        } catch (AuthenticationException $e) {
            // Catch AuthenticationException and return an unauthorized response
            return api_response(errors: [[$e->getMessage()], 'Unauthorized access'], message: 'Invalid credentials', code: 401);
        } catch (ValidationException $e) {
            // Catch ValidationException and return a validation error response
            return api_response(errors: [$e->errors()], message: 'Validation error', code: 422);
        } catch (Exception $e) {
            return api_response(errors: [$e->getMessage()], message: 'Login error', code: 500);
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
            $request->user()->currentAccessToken()->delete();

            return api_response(message: 'Successfully logged out');

        } catch (\Exception $e) {
            // Handle any exceptions that might occur during logout
            return api_response(errors: [$e->getMessage()], message: 'Logged out error');
        }
    }
}

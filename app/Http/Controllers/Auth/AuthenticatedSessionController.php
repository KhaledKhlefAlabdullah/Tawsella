<?php

namespace App\Http\Controllers\Auth;

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

            // Delete all existing tokens for the authenticated user
            //todo un commit this in production
//            $request->user()->tokens()->delete();

            // Get user details
            $user = $request->user();
            $user->device_token = $request->input('device_token');
            $user->save();
            $taxi = $user->taxi;

            if($user->hasRole(\App\Enums\UserEnums\UserType::TaxiDriver()->key) && !$taxi){
                return api_response(message: 'You Cannot logged in because you don\'t have taxi', code: 403);
            }

            // Create a new token for the user
            $token = createUserToken($user, 'login-token');

            return api_response(data: ['token' => $token, 'user' => $user, 'profile' => $user->profile, 'mail_code_verified_at' => $user->mail_code_verified_at], message: 'Successfully logged in');

        } catch (AuthenticationException $e) {
            // Catch AuthenticationException and return an unauthorized response
            return api_response(message: 'Invalid credentials', code: 401, errors: [[$e->getMessage()], 'Unauthorized access']);
        } catch (ValidationException $e) {
            // Catch ValidationException and return a validation error response
            return api_response(message: 'Validation error', code: 422, errors: [$e->errors()]);
        } catch (Exception $e) {
            return api_response(message: 'Login error', code: 500, errors: [$e->getMessage()]);
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
            return api_response(message: 'Logged out error', errors: [$e->getMessage()]);
        }
    }
}

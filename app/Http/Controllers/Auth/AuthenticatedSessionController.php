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
                $token = createToken($user,'login-token');

                return api_response(data: ['token' => $token, 'user' => $user], message: 'Login successful');
            }

            $request->session()->regenerate();

            return redirect()->intended(RouteServiceProvider::HOME);
        } catch (AuthenticationException $e) {
            // Catch AuthenticationException and return an unauthorized response
            return api_response(errors:[$e->getMessage(),'Unauthorized'],message:'Invalid credentials',code:401);
        } catch (ValidationValidationException $e) {
            // Catch ValidationException and return a validation error response
            return api_response(errors:[$e->errors()],message:'Validation Error',code:422);
        } catch(Exception $e){
            return api_response(errors:[$e->getMessage()],message:'login error',code:500);
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}

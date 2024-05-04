<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserProfile;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
                'phone_number' => ['nullable', 'string', 'regex:/^(00|\+)[0-9]{9,20}$/'],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            $user = User::create([
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            UserProfile::create([
                'user_id' => $user->id,
                'name' => $request->input('name'),
                'phone_number' => $request->input('phone_number'),
                'gender' => $request->input('gender')
            ]);

            // event(new Registered($user));

            $user->sendEmailVerificationNotification(true);

            $token = createUserToken($user, 'register-token');

            return api_response(data: ['token' => $token, 'user_id' => $user->id], message: 'register-success');
        } catch (Exception $e) {
            return api_response(errors: $e->getMessage(), message: 'register-error', code: 500);
        }
    }
}

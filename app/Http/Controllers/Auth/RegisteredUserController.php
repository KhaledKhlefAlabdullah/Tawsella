<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UserRequest;
use App\Models\User;
use App\Models\UserProfile;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(UserRequest $request)
    {
        try {

            DB::beginTransaction();

            $request->validated();

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

            DB::commit();
            return api_response(data: ['token' => $token, 'user_id' => $user->id], message: 'register-success');
        } catch (Exception $e) {

            DB::rollBack();
            return api_response(errors: $e->getMessage(), message: 'register-error', code: 500);
        }
    }
}

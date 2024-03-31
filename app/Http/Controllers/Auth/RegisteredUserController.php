<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Taxi;
use App\Models\User;
use App\Models\UserProfile;
use App\Providers\RouteServiceProvider;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

use function PHPUnit\Framework\isNull;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, string $user_type = 'customer')
    {
        try {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
                'phone_number' => ['required', 'string', 'regex:/^\+[0-9]{9,20}$/'],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            $user = User::create([
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'user_type' => $user_type,
                'driver_state' => $user_type == 'driver' ? 'ready': null
            ]);
            
            UserProfile::create([
                'user_id' => $user->id,
                'name' => $request->input('name'),
                'gender' => $user_type == 'driver' ? $request->gender : null,
                'phoneNumber' => $request->input('phone_number'),
            ]);
            
            if ($request->wantsJson()) {

                $token = createToken($user, 'register-token');

                return api_response(data: ['token' => $token, 'user_id' => $user->id], message: 'register-success');
            }

            Session::flash('success', 'Driver account created successfully.');

            // Redirect back or to any other page
            return redirect()->back();

        } catch (Exception $e) {
            if(request()->wantsJson())
                return api_response(errors: [$e->getMessage()], message: 'register-error', code: 500);
            }
            return redirect()->back()->withErrors([$e->getMessage()])->withInput(); // Provide feedback on error

    }
    

    /**
     * Admin register to create driver account
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function admin_store(Request $request)
    {
        $request->validate([
            'gender' => ['required','string','in:male,female']
        ]);
        //dd($request);
        return $this->store($request, 'driver');
    }
}

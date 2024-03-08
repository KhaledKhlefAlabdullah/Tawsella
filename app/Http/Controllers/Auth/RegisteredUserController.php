<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserProfile;
use App\Providers\RouteServiceProvider;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

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
    public function store(Request $request)
    {
        try{

            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);
    
            $user = User::create([
                'email' => $request->email,
                'password' => password_hash($request->password,PASSWORD_DEFAULT),
                'user_type' => 'customer'
            ]);
            
            UserProfile::create([
                'user_id' => $user->id,
                'name' => $request->input('name')
            ]);

            if($request->wantsJson()){

                $token = createToken($user,'register-token');

                return api_response(data:$token,message:'register-success');

            }

            event(new Registered($user));
    
            Auth::login($user);
    
            return redirect(RouteServiceProvider::HOME);
        }
        catch(Exception $e){
            return api_response(errors:[$e->getMessage()],message:'register-error',code:500);
        }
       
    }
}

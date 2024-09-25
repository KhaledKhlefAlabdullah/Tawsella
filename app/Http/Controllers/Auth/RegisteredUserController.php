<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserEnums\UserType;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequests\UserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     * @return JsonResponse|\Illuminate\Validation\ValidationException if request want json return json response if not redirect to main page
     * @returns
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(UserRequest $request)
    {
       return User::registerUser($request);
    }
}

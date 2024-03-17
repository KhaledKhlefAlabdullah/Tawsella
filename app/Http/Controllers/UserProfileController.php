<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserProfileRequest;
use App\Models\Taxi;
use App\Models\User;
use App\Models\UserProfile;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(UserProfile $userProfile)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserProfile $userProfile)
    {
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserProfileRequest $request, string $id)
    {
        try {

            $request->validated();

            $user = findAndUpdate(User::class, $id, ['email'], [$request->input('email')]);

            $userProfile = findAndUpdate(UserProfile::class, $id, ['name', 'phoneNumber'], [$request->input('name'), $request->input('phoneNumber')]);

            if ($request->user_avatar) {

                $avatar = $request->user_avatar;

                $path = 'images/profiles';

                if ($userProfile->user_avatar == '/images/profile_images/user_profile.png') {

                    $avatar_path = storeProfileAvatar($avatar, $path);
                } else {

                    $avatar_path = editProfileAvatar($userProfile->user_avatar, $path, $avatar);
                }

                $userProfile->update([
                    'user_avatar' => $avatar_path
                ]);
            }

            if (Auth::user()->user_type == 'admin') {

                $taxi = findAndUpdate(Taxi::class, $id, ['lamp_number', 'plate_number'], [$request->input('carLampNumber'), $request->input('carPlatNumber')]);
            }

            if ($request->wantsJson())
                return api_response(message: 'profile-edite-success');

            return redirect()->view('');
        } catch (Exception $e) {
            if ($request->wantsJson())
                return api_response(errors: [$e->getMessage()], message: 'profile-edite-error', code: 500);

            return abort(message: 'there error in update user details', code: 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserProfile $userProfile)
    {
        //
    }
}

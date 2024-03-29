<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserProfileRequest;
use App\Models\Taxi;
use App\Models\User;
use App\Models\UserProfile;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use function Laravel\Prompts\error;

class UserProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {

            $profile = UserProfile::select('users.id as user_id', 'user_profiles.name', 'user_profiles.avatar', 'user_profiles.phoneNumber', 'users.email')
                ->join('users', 'user_profiles.user_id', '=', 'users.id')
                ->where('user_profiles.user_id', getMyId())
                ->first();

            return api_response(data: $profile, message: 'user profile details getting success');
        } catch (Exception $e) {
            return api_response(errors: $e->getMessage(), message: 'user profile details getting error', code: 500);
        }
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
            $user = getAndCheckModelById(User::class, $id);
            $user->update(['email' => $request->input('email')]);

            $userProfile = UserProfile::where('user_id', $id)->first();

            $userProfile->update([
                'name' => $request->input('name'),
                'phoneNumber' => $request->input('phoneNumber')
            ]);

            if ($request->avatar) {

                $avatar = $request->avatar;

                $path = 'images/profiles';

                if ($userProfile->avatar == '/images/profile_images/user_profile.png') {

                    $avatar_path = storeProfileAvatar($avatar, $path);
                } else {

                    $avatar_path = editProfileAvatar($userProfile->avatar, $path, $avatar);
                }

                $userProfile->update([
                    'avatar' => $avatar_path
                ]);
            }

            if (!is_null(Auth::user())) {
                if (Auth::user()->user_type == 'admin') {

                    $taxi = Taxi::where('driver_id', $id)->first();
                    $taxi->update([
                        'lamp_number' => $request->input('carLampNumber'),
                        'plate_number' => $request->input('carPlatNumber')
                    ]);
                }
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
}

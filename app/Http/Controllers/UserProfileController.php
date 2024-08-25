<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserProfileRequest;

use App\Models\User;
use App\Models\UserProfile;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use function Laravel\Prompts\error;

class UserProfileController extends Controller
{

    /**
     * Return Auth User Profile
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-13
     * @return JsonResponse UserProfile data
     */
    public function index()
    {
        try {

            $use = Auth::user();

            $profile = [
                'id' => $use->id,
                'name' => $use->profile->name,
                'avatar' => $use->profile->avatar,
                'phone_number' => $use->profile->phone_number,
                'email' => $use->email
            ];

            return api_response(data: $profile, message: 'Profile retrieved successfully.');
        } catch (Exception $e) {
            return api_response(errors: $e->getMessage(), message: 'Profile retrieved error.', code: 500);
        }
    }

    /**
     * Update the specified resource in storage.
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-14
     * @param UserProfileRequest $request is profile data
     * @param User $user is user to edit his profile
     * @return JsonResponse
     */
    public function update(UserProfileRequest $request, User $user)
    {
        try {

            $request->validated();

            if (request()->has('email'))
                $user->update([
                    'email' => $request->input('email')
                ]);

            if (request()->has('password'))
                $user->update([
                    'password' => Hash::make($request->password),
                ]);

            $userProfile = $user->profile();

            $userProfile->update([
                'name' => $request->input('name'),
                'phone_number' => $request->input('phone_number')
            ]);

            if ($request->has('avatar')) {

                if (!empty($request->input('avatar'))) {
                    $avatar = $request->avatar;

                    $path = 'images/profiles';

                    if ($userProfile->avatar == '/images/profile_images/user_profile.png') {

                        $avatar_path = storeFile($avatar, $path);
                    } else {

                        $avatar_path = editFile($userProfile->avatar, $path, $avatar);
                    }

                    $userProfile->update([
                        'avatar' => $avatar_path
                    ]);
                }
            }

            return api_response(message: 'Profile updated successfully.');
        } catch (Exception $e) {
            return api_response(errors: [$e->getMessage()], message: 'Profile updated error.', code: 500);
        }
    }

}

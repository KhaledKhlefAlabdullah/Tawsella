<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequests\UserRequest;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;



class UserProfileController extends Controller
{

    /**
     * Return Auth User Profile
     * @return JsonResponse UserProfile data
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-13
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
            return api_response(errors: [$e->getMessage()], message: 'Profile retrieved error.', code: 500);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param UserRequest $request is profile data
     * @return JsonResponse
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-14
     */
    public function update(UserRequest $request)
    {
        try {
            DB::beginTransaction();
            $validatedData = $request->validated();
            $user = Auth::user();
            if ($request->has('email'))
                $user->update([
                    'email' => $validatedData['email']
                ]);
            if ($request->has('password'))
                $user->update([
                    'password' => Hash::make($request->password),
                ]);
            $userProfile = $user->profile();
            $userProfile->update([
                'name' => $validatedData['name'],
                'phone_number' => $validatedData['phone_number']
            ]);
            if ($request->hasFile('avatar')) {
                if (!empty($validatedData['avatar'])) {
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
            DB::commit();
            return api_response(message: 'Profile updated successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            return api_response(errors: [$e->getMessage()], message: 'Profile updated error.', code: 500);
        }
    }
}

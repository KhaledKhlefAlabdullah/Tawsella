<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserProfileRequest;

use App\Models\User;
use App\Models\UserProfile;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use function Laravel\Prompts\error;

class UserProfileController extends Controller
{

    /**
     * Return Auth User Profile
     * @return mixed UserProfile data
     */
    public function index()
    {
        try {

            $profile = UserProfile::select('users.id as user_id', 'user_profiles.name', 'user_profiles.avatar', 'user_profiles.phoneNumber', 'users.email')
                ->join('users', 'user_profiles.user_id', '=', 'users.id')
                ->where('user_profiles.user_id', getMyId())
                ->first();

            return api_response(data: $profile, message: 'تفاصيل ملف تعريف المستخدم تحقق النجاح');
        } catch (Exception $e) {
            return api_response(errors: $e->getMessage(), message: 'تفاصيل ملف تعريف المستخدم تحصل على خطأ', code: 500);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param UserProfileRequest $request is profile data
     * @param string $id is user id to edit his profile
     * @return mixed
     */
    public function update(UserProfileRequest $request, string $id)
    {
        try {

            $request->validated();
            $user = getAndCheckModelById(User::class, $id);
            if (request()->has('email'))
                $user->update([
                    'email' => $request->input('email')
                ]);

            if (request()->has('password'))
                $user->update([
                    'password' => Hash::make($request->password),
                ]);

            $userProfile = UserProfile::where('user_id', $id)->first();

            $userProfile->update([
                'name' => $request->input('name'),
                'phoneNumber' => $request->input('phoneNumber')
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

            return api_response(message: 'تم تعديل البيانات بنجاح');
        } catch (Exception $e) {
            return api_response(errors: [$e->getMessage()], message: 'حدث خطأ في تعديل البيانات', code: 500);
        }
    }

}

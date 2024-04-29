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
     * Display a listing of the resource.
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
     * Show the form for editing the specified resource.
     */
    public function edit(UserProfile $userProfile)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    // تلقا بشقك
    // غير قابل للتعديل
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

                        $avatar_path = storeProfileAvatar($avatar, $path);
                    } else {

                        $avatar_path = editProfileAvatar($userProfile->avatar, $path, $avatar);
                    }

                    $userProfile->update([
                        'avatar' => $avatar_path
                    ]);
                }
            }

            if ($request->wantsJson())
                return api_response(message: 'تم تعديل البيانات بنجاح');

            return redirect()->back()->with('success', 'تم تعديل بيانات السائق بنجاح');
        } catch (Exception $e) {
            if ($request->wantsJson())
                return api_response(errors: [$e->getMessage()], message: 'حدث خطأ في تعديل البيانات', code: 500);

            return redirect()->back()->withErrors('هنالك خطأ في جلب البيانات الرجاء المحاولة مرة أخرى. الاخطاء:' . $e->getMessage())->withInput();
        }
    }
}

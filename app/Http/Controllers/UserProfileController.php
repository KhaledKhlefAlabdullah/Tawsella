<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequests\UserRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;


class UserProfileController extends Controller
{

    /**
     * Return Auth User Profile
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|JsonResponse UserProfile data
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-13
     */
    public function index()
    {
        try {

            if (!request()->wantsJson()) {
                return view('profile.profile');
            }

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
     * Display the user's profile form.
     * @param Request $request
     * @return View
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-14
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param UserRequest $request is profile data
     * @return JsonResponse|\Illuminate\Http\RedirectResponse
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

            if ($request->wantsJson()) {
                return api_response(message: 'Profile updated successfully.');
            }
            return redirect()->back()->with('success', 'تم تحديث البيانات بنجاح');

        } catch (Exception $e) {
            DB::rollBack();
            return api_response(errors: [$e->getMessage()], message: 'Profile updated error.', code: 500);
        }
    }

    /**
     * Delete the user's account.
     * @param Request $request
     * @return RedirectResponse
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}

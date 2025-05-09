<?php

namespace App\Http\Controllers;

use App\Enums\UserEnums\UserGender;
use App\Http\Requests\UserRequests\UserRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;



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
            $user = Auth::user();
            $profile = [
                'id' => $user->id,
                'name' => $user->profile?->name,
                'gender' => UserGender::getKey($user->profile?->gender ?? 0),
                'avatar' => $user->profile?->avatar,
                'phone_number' => $user->profile?->phone_number,
                'email' => $user->email,
                'address' => $user->profile?->address,
            ];
            return api_response(data: $profile, message: 'تم جلب بيانات الملف الشخصي بنجاح');
        } catch (Exception $e) {
            return api_response(message: 'هناك خطأ في جلب بيانات الملف الشخصي', code: 500, errors: [$e->getMessage()]);
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
            $user = User::where('id',Auth::id())->first();
            User::handelUpdateDetails($user, $validatedData);

            $profile = [
                'id' => $user->id,
                'name' => $user->profile?->name,
                'avatar' => $user->profile?->avatar,
                'phone_number' => $user->profile?->phone_number,
                'email' => $user->email,
                'address' => $user->profile?->address,
            ];

            DB::commit();
            return api_response(data: $profile,message: 'تم تحديث الملف الشخصي بنجاح');
        } catch (Exception $e) {
            DB::rollBack();
            return api_response(message: 'هناك خطأ في تحديث الملف الشخصي', code: 500, errors: [$e->getMessage()]);
        }
    }

    /**
     * @param User $user
     * @return JsonResponse
     */
    public function destroy(User $user){
        try {
            DB::beginTransaction();
            $user->delete();
            DB::commit();
            return api_response(message: 'تم حذف المستخدم بنجاح');
        }catch (Exception $e) {
            DB::rollBack();
            return api_response(message: 'هناك خطأ في حذف الحساب', code: 500, errors: [$e->getMessage()]);
        }
    }
}

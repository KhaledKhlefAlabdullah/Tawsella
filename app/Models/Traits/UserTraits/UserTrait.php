<?php

namespace App\Models\Traits\UserTraits;

use App\Enums\UserEnums\UserGender;
use App\Enums\UserEnums\UserType;
use App\Http\Requests\UserRequests\UserRequest;
use App\Models\Rating;
use App\Models\TaxiMovement;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Exception;
use Illuminate\Support\Facades\Session;

trait UserTrait
{
    /**
     * Mapping users
     * @param $users
     * @return mixed
     * @throws \BenSampo\Enum\Exceptions\InvalidEnumMemberException
     */
    public static function mappingUsers($users)
    {
        return $users->map(function ($user) {
            // Basic user mapping
            $mapping = [
                'user_id' => $user->id,
                'name' => $user->profile?->name ?? '',
                'points' => $user->points ?? 0,
                'email' => $user->email,
                'phone_number' => $user->profile?->phone_number ?? '',
                'avatar' => $user->profile?->avatar ?? '',
                'is_active' => $user->is_active,
                'user_type' => $user->getRoleNames()[0],
                'gender' => UserGender::getKey($user->profile?->gender ?? 0),
                'created_at' => $user->created_at,
            ];

            // Movement counts (customer or driver)
            if ($user->relationLoaded('customer_movements') || $user->relationLoaded('driver_movements')) {
                $canceledMovements = count_items(TaxiMovement::class, ['customer_id' => $user->id, 'is_canceled' => true]);
                $completedMovements = count_items(TaxiMovement::class, ['customer_id' => $user->id, 'is_completed' => true]);
                $mapping = array_merge($mapping, ['completed_movements' => $completedMovements, 'canceled_movements' => $canceledMovements]);
            }

            // Additional driver-related information
            if (!in_array($user->getRoleNames(), [UserType::Customer()->key, UserType::Admin()->key])) {
                $ratingsNum = count_items(Rating::class, ['driver_id' => $user->id]);
                $userRating = $user->rating;
                $rating = $userRating ? $userRating->sum('rating') / $ratingsNum : null;

                $vehicle = $user->vehicle;

                $mapping = array_merge($mapping, [
                    'ratings_number' => $ratingsNum,
                    'rating' => $rating,
                    'plat_number' => $vehicle->plat_number ?? '',
                    'vehicle_image' => $vehicle->vehicle_image ?? '',
                    'vehicle_description' => $vehicle->vehicle_description ?? '',
                    'last_location_latitude' => $user->last_location_latitude ?? '',
                    'last_location_longitude' => $user->last_location_longitude ?? '',
                ]);
            }

            return $mapping;
        });
    }

    /**
     * Register new user in database
     * @param UserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public static function registerUser(UserRequest $request)
    {
        try {
            DB::beginTransaction();

            $validatedData = $request->validated();

            // Create the user
            $user = User::create([
                'device_token' => $validatedData['device_token'] ?? null,
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
            ]);

            // Create user profile
            UserProfile::create([
                'user_id' => $user->id,
                'name' => $validatedData['name'],
                'gender' => UserGender::getValue($validatedData['gender']),
                'phone_number' => $validatedData['phone_number'],
                'address' => $validatedData['address'] ?? null,
                'birthdate' => $validatedData['birthdate'] ?? null
            ]);

            // Assign role based on authe
            $authUser = Auth::user();
            if ($authUser && $authUser->hasRole(UserType::Admin()->key) || $authUser->hasRole(UserType::Moderator()->key)) {
                $user->assignRole(UserType::TaxiDriver()->key);
                $user->mail_code_verified_at = now();
                $user->save();
                DB::commit();

                return api_response(data: ['user' => $user, 'profile' => $user->profile], message: 'تم إنشاء حساب السائق بنجاح');
            }

            // Default role for other users
            $user->assignRole(UserType::Customer()->key);
            // $user->sendEmailVerificationNotification(true);

            // Generate a token for the newly registered user
            $token = createUserToken($user, 'register-token');

            DB::commit();
            return api_response(data: ['token' => $token, 'user' => $user, 'profile' => $user->profile, 'mail_code_verified_at' => $user->mail_code_verified_at], message: 'تم إنشاء الحساب بنجاح');

        } catch (QueryException $e) {
            DB::rollBack();
            return api_response(message: 'حصل خطأ في قاعدة البيانات اثناء التسجيل', code: 500, errors: [$e->getMessage()]);
        } catch (Exception $e) {
            DB::rollBack();
            return api_response(message: 'فشل تسجيل الحساب', code: 500, errors: [$e->getMessage()]);
        }
    }

    /**
     * @param User $user
     * @param $validatedData
     * @return void
     */
    public static function handelUpdateDetails(User $user, $validatedData)
    {
        if (array_key_exists('email', $validatedData))
            $user->update([
                'email' => $validatedData['email']
            ]);
        if (array_key_exists('password', $validatedData))
            $user->update([
                'password' => Hash::make($validatedData['password']),
            ]);

        $userProfile = UserProfile::where('user_id', $user->id)->first();

        $userProfile->update([
            'name' => $validatedData['name'] ?? $userProfile->name,
            'phone_number' => $validatedData['phone_number'] ?? $userProfile->phone_number,
            'address' => $validatedData['address'] ?? $userProfile->address,
            'birthdate' => $validatedData['birthdate'] ?? $userProfile->birthdate,
            'gender' => array_key_exists('gender', $validatedData) ? UserGender::getValue($validatedData['gender']) : $userProfile->gender,
        ]);

        if (array_key_exists('avatar', $validatedData)) {
            $avatar = $validatedData['avatar'];
            $path = '/images/profile';
            $avatar_path = editFile($userProfile->avatar, $path, $avatar);

            $userProfile->update([
                'avatar' => $avatar_path
            ]);
        }

        if (!array_key_exists('avatar', $validatedData) && array_key_exists('gender', $validatedData)) {

            $avatar_path = UserGender::getValue($validatedData['gender']) == UserGender::male
                ? '/images/profile/man.png'
                : '/images/profile/woman.png';

            $userProfile->update([
                'avatar' => $avatar_path
            ]);
        }
        return $userProfile;
    }
}

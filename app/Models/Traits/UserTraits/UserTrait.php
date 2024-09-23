<?php

namespace App\Models\Traits\UserTraits;

use App\Enums\UserEnums\UserGender;
use App\Enums\UserEnums\UserType;
use App\Http\Requests\UserRequests\UserRequest;
use App\Models\Rating;
use App\Models\TaxiMovement;
use App\Models\User;
use App\Models\UserProfile;
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
                'name' => $user->profile->name ?? '',
                'points' => $user->points ?? 0,
                'email' => $user->email,
                'phone_number' => $user->profile->phone_number ?? '',
                'avatar' => $user->profile->avatar ?? '',
                'is_active' => $user->is_active,
                'user_type' => $user->getRoleNames()[0],
                'gender' => $user->profile->gender ?? '',
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

    public static function registerUser(UserRequest $request){
        try {
            DB::beginTransaction();
            $validatedData = $request->validated();

            $user = User::create([
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
            ]);

            UserProfile::create([
                'user_id' => $user->id,
                'name' => $validatedData['name'],
                'gender' => UserGender::getValue($validatedData['gender']),
                'phone_number' => $validatedData['phone_number'],
            ]);

            if ($request->wantsJson()) {
                $user->assignRole(UserType::Customer()->key);

                $user->sendEmailVerificationNotification(true);

                $token = createUserToken($user, 'register-token');
                DB::commit();
                return api_response(data: ['token' => $token, 'user_id' => $user->id, 'mail_code_verified_at' => $user->mail_code_verified_at], message: __('register-success'));
            }

            $user->assignRole(UserType::TaxiDriver()->key);

            $user->mail_code_verified_at = now();
            $user->save();
            Session::flash('success', __('register-success'));
            DB::commit();

            // Redirect back or to any other page
            return redirect()->back();

        } catch (Exception $e) {
            DB::rollBack();
            if (request()->wantsJson()) {
                return api_response(errors: $e->getMessage(), message: __('register-error'), code: 500);
            }
            return redirect()->back()->withErrors(__('register-error')."\n errors:" . $e->getMessage())->withInput();
        }
    }
}

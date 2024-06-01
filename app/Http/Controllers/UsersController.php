<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\UserRequest;
use App\Models\Movement;
use App\Models\Rating;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use App\Models\UserProfile;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    /**
     * Retrieve details of all users except admin.
     *
     * @return mixed Details of users including name, email, phone number, user type,
     *               creation date, and activation status.
     */
    public function index()
    {
        try {
            // Get IDs of all users except admin
            $users_ids = User::where('id', '!=', Auth::id())->pluck('id');

            // Fetch user details
            $users = User::select('users.id', 'user_profiles.name', 'users.email', 'user_profiles.phoneNumber', 'user_profiles.avatar', 'users.is_active', 'users.created_at')
                ->join('user_profiles', 'users.id', '=', 'user_profiles.user_id')
                ->whereIn('users.id', $users_ids) // Filter users except admin
                ->get();

            // Return API response with user data
            return api_response(data: $users, message: 'تم جلب بيانات المستخدمين بنجاح');
        } catch (Exception $e) {
            // Return error response if an exception occurs
            return api_response(errors: $e->getMessage(), message: 'هناك مشكلة في جلب بيانات المستخدمين', code: 500);
        }
    }

    /**
     * Store a newly created user.
     *
     * @param UserRequest $request New user information.
     * @return mixed API response confirming the creation of the user.
     */
    public function store(UserRequest $request)
    {
        try {
            // Validate the incoming data
            $validatedData = $request->validated();

            // Create user
            $user = User::create([
                'email' => $validatedData['email'],
                'password' => $validatedData['password'],
                'user_type' => $validatedData['user_type'],
                'mail_code_verified_at' => now(),
            ]);

            // Create user profile
            UserProfile::create([
                'user_id' => $user->id,
                'name' => $validatedData['name']
            ]);

            // Prepare response data
            $data = [
                'email' => $validatedData['email'],
                'password' => $validatedData['password'],
                'user_type' => $validatedData['user_type'],
                'name' => $validatedData['name']
            ];

            // Return API response with newly created user data
            return api_response(data: $data, message: 'تم إنشاء مستخدم جديد بنجاح');
        } catch (Exception $e) {
            // Return error response if an exception occurs
            return api_response(errors: $e->getMessage(), message: 'هناك مشكلة في إنشاء مستخدم جديد', code: 500);
        }
    }

    /**
     * Retrieve details of a specific user.
     *
     * @param string $id User ID to retrieve details.
     * @return mixed $user_details with user details by user type
     */

    public function show($id)
    {
        try {
            // Define the initial query fields
            $query = ['users.id', 'up.name', 'users.email', 'up.phoneNumber', 'up.avatar', 'users.is_active', 'users.created_at'];

            // Get the user type of the specified user by ID
            $user_type = getAndCheckModelById(User::class, $id)->user_type;

            // Check if the user type is not 'customer'
            if ($user_type != 'customer') {
                // If not a customer, add additional fields related to vehicles and location to the query
                $additionalQuery = [
                    'vehicles.plate_number',
                    'vehicles.vehicle_image',
                    'vehicles.vehicle_description',
                    'users.last_location_latitude',
                    'users.last_location_longitude'
                ];

                $query = array_merge($query, $additionalQuery);

                $compare_id = 'driver_id';
            } else {
                $compare_id = 'customer_id';
            }

            // Fetch user details
            $user = User::select($query)
                ->join('user_profiles as up', 'users.id', '=', 'up.user_id')
                ->leftJoin('vehicles as v', 'users.id', '=', 'v.driver_id')
                ->where('users.id', $id) // Filter users except admin
                ->first();

            // Count completed and canceled movements based on user type
            $completed_movements = count_items(Movement::class, [$compare_id => $id, 'is_completed' => true]);
            $canceled_movements = count_items(Movement::class, [$compare_id => $id, 'is_canceled' => true]);

            // Prepare user details array
            $user_details = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'user_type' => $user->user_type,
                'phone_number' => $user->phoneNumber,
                'profile_image' => $user->avatar,
                'is_active' => $user->is_active,
                'completed_movements' => $completed_movements,
                'canceled_movements' => $canceled_movements,
            ];

            // If the user is not a customer, add additional details
            if ($user_type != 'customer') {
                // Count and calculate driver ratings
                $ratings = count_items(Rating::class, ['driver_id' => $id]);
                $rating = $ratings > 0 ? Rating::where('driver_id', $id)->sum('rating') / $ratings : 0;

                // Prepare driver details array
                $driverDetails = [
                    'rating' => $rating,
                    'vehicle_image' => $user->vehicle_image,
                    'plat_number' => $user->plate_number,
                    'vehicle_description' => $user->vehicle_description,
                    'last_location_latitude' => $user->last_location_latitude,
                    'last_location_longitude' => $user->last_location_longitude
                ];

                // Merge driver details with user details array
                $user_details = array_merge($user_details, $driverDetails);
            }



            // Return API response with user details
            return api_response(data: $user_details, message: 'تم جلب تفاصل الحساب بنجاح');
        } catch (Exception $e) {
            // Handle any exceptions
            return api_response(errors: $e->getMessage(), message: 'هناك خطأ في جلب تفاثيل الحساب', code: 500);
        }
    }


    /**
     * Update the details of a user.
     *
     * @param UserRequest $request New user information.
     * @param  string $id User ID to update.
     * @return mixed API response confirming the update of the user.
     */
    public function update(UserRequest $request, string $id)
    {
        try {
            // Validate the incoming data
            $validatedData = $request->validated();

            // Find the user by ID
            $user = getAndCheckModelById(User::class, $id);

            // Update user details
            $user->update([
                'email' => $validatedData['email'],
                'password' => $validatedData['password'],
                'user_type' => $validatedData['user_type'],
            ]);

            // Update user profile
            $user->profile->update([
                'name' => $validatedData['name']
            ]);

            // Prepare response data
            $data = [
                'email' => $validatedData['email'],
                'password' => $validatedData['password'],
                'user_type' => $validatedData['user_type'],
                'name' => $validatedData['name']
            ];

            // Return API response confirming the update
            return api_response(data: $data, message: 'تم تعديل بيانات المستخدم بنجاح');
        } catch (Exception $e) {
            // Return error response if an exception occurs
            return api_response(errors: $e->getMessage(), message: 'هناك مشكلة في تحديث بيانات المستخدم', code: 500);
        }
    }

    /**
     * Set the activation status of a user.
     *
     * @param Request $request Request containing activation status.
     * @param  string $id User ID to update activation status.
     * @return mixed API response confirming the activation status change.
     */
    public function setActive(UserRequest $request, string $id)
    {
        try {
            // Validated data
            $validatedData = $request->validated();

            // Find the user by ID
            $user = getAndCheckModelById(User::class, $id);

            // Update activation status
            $user->update([
                'is_active' => $validatedData['active']
            ]);

            // Define message based on activation status
            $message = $validatedData['active'] ? 'تم تفعيل الحساب بنجاح' : 'تم إلغاء تفعيل الحساب بنجاح';

            // Return API response with updated activation status
            return api_response(data: $user->is_active, message: $message, code: 200);
        } catch (Exception $e) {
            // Return error response if an exception occurs
            return api_response(errors: $e->getMessage(), message: 'هناك خطأ في تغيير حالة الحساب', code: 500);
        }
    }

    /**
     * Delete a user.
     *
     * @param  string $id User ID to delete.
     * @return mixed API response confirming the deletion of the user.
     */
    public function destroy(string $id)
    {
        try {
            // Find the user by ID
            $user = getAndCheckModelById(User::class, $id);

            // Delete the user
            $user->delete();

            // Return API response confirming the deletion
            return api_response('تم حذف المستخدم بنجاح', 200);
        } catch (Exception $e) {
            // Return error response if an exception occurs
            return api_response(errors: $e->getMessage(), message: 'هناك مشكلة في حذف الحساب', code: 500);
        }
    }
}

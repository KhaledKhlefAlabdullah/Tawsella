<?php

namespace App\Http\Controllers;

use App\Enums\UserType;
use App\Http\Requests\Auth\UserRequest;
use App\Models\Movement;
use App\Models\Rating;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use App\Models\UserProfile;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller
{
    /**
     * Retrieve details of all drivers.
     * @return JsonResponse Details of users including name, email, phone number, user type,
     *               creation date, and activation status.
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-8
     */
    public function getDrivers()
    {
        try {
            // Fetch paginated drivers
            $drivers = User::with('profile')
                ->whereNotIn('user_type', [UserType::ADMIN(), UserType::CUSTOMER()])
                ->paginate(10); // Paginate drivers

            // Return API response with user data and pagination metadata
            return api_response(
                data: User::mappingUsers(collect($drivers->items())),
                message: 'Successfully retrieved drivers',
                pagination: $drivers
            );
        } catch (Exception $e) {
            // Return error response if an exception occurs
            return api_response(errors: [$e->getMessage()], message: 'retrieved drivers error', code: 500);
        }
    }

    /**
     * Retrieve details of all customers.
     * @return JsonResponse Details of users including name, email, phone number, user type,
     *               creation date, and activation status.
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-8
     */
    public function getCustomers()
    {
        try {
            // Fetch paginated customers
            $customers = User::with('profile')
                ->where('user_type', UserType::CUSTOMER())
                ->paginate(10); // Paginate customers

            // Return API response with user data and pagination metadata
            return api_response(
                data: User::mappingUsers(collect($customers->items())),
                message: 'Successfully retrieved customers',
                pagination: $customers,
            );
        } catch (Exception $e) {
            // Return error response if an exception occurs
            return api_response(errors: [$e->getMessage()], message: 'retrieved customers error', code: 500);
        }
    }


    /**
     * Store a newly created user.
     * @param UserRequest $request New user information.
     * @return JsonResponse API response confirming the creation of the user.
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-7
     */
    public function store(UserRequest $request)
    {
        try {
            DB::beginTransaction();
            // Validate the incoming data
            $validatedData = $request->validated();

            // Create user
            $user = User::create([
                'email' => $validatedData['email'],
                'password' => $validatedData['password'],
                'user_type' => UserType::fromKey($validatedData['user_type']),
                'is_active' => $validatedData['active'],
                'mail_code_verified_at' => now()
            ]);

            // Create user profile
            $user->profile()->create([
                'user_id' => $user->id,
                'name' => $validatedData['name'],
                'phone_number' => $validatedData['phone_number'],
                'gender' => $validatedData['gender']
            ]);

            $user->assignRole($validatedData['user_type']);

            DB::commit();
            // Return API response with newly created user data
            return api_response(data: $user->id, message: 'Successfully created user');
        } catch (Exception $e) {
            DB::rollBack();
            // Return error response if an exception occurs
            return api_response(errors: [$e->getMessage()], message: 'created user error', code: 500);
        }
    }

    /**
     * Retrieve details of a specific user.
     * @param User $user User to retrieve details.
     * @return JsonResponse $user_details with user details by user type
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-9
     */
    public function show(User $user)
    {
        try {
            // Define the initial query fields

            $userDetails = User::with(['profile', 'customer_movements'])
                ->where('id', $user->id)->get();

            // Return API response with user details
            return api_response(data: User::mappingUsers($userDetails), message: 'Successfully getting user details');
        } catch (Exception $e) {
            // Handle any exceptions
            return api_response(errors: [$e->getMessage()], message: 'getting user details error', code: 500);
        }
    }


    /**
     * Update the details of a user.
     * @param UserRequest $request New user information.
     * @param User $user User to update.
     * @return JsonResponse API response confirming the update of the user.
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-10
     */
    public function update(UserRequest $request, User $user)
    {
        try {
            DB::beginTransaction();
            // Validate the incoming data
            $validatedData = $request->validated();

            // Update user details
            $user->update([
                'email' => $validatedData['email'] ?? $user->email,
                'password' => $validatedData['password'] ?? $user->password,
                'user_type' => $validatedData['user_type'] ?? $user->user_type,
                'is_active' => $validatedData['active']
            ]);

            // Update user profile
            $user->profile->update([
                'name' => $validatedData['name'] ?? $user->profile->name,
                'phone_number' => $validatedData['phone_number'] ?? $user->profile->phone_number,
                'gender' => $validatedData['gender'] ?? $user->profile->gender,
            ]);

            DB::commit();
            // Return API response confirming the update
            return api_response(message: 'Successfully updated user');
        } catch (Exception $e) {
            DB::rollBack();
            // Return error response if an exception occurs
            return api_response(errors: [$e->getMessage()], message: 'updated user error', code: 500);
        }
    }

    /**
     * Set the activation status of a user.
     * @param Request $request Request containing activation status.
     * @param User $user User ID to update activation status.
     * @return JsonResponse API response confirming the activation status change.
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-12
     */
    public function setActive(UserRequest $request, User $user)
    {
        try {
            // Validated data
            $validatedData = $request->validated();

            // Update activation status
            $user->update([
                'is_active' => $validatedData['active']
            ]);

            // Define message based on activation status
            $message = $validatedData['active'] ? 'Successfully activate the account' : 'Successfully deactivate the account';

            // Return API response with updated activation status
            return api_response(data: $user->is_active, message: $message, code: 200);
        } catch (Exception $e) {
            // Return error response if an exception occurs
            return api_response(errors: [$e->getMessage()], message: 'Error in activate or deactivate account', code: 500);
        }
    }

    /**
     * Get the list of user types
     * @return \Illuminate\Http\JsonResponse
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-7
     * This method retrieves the possible user types from the UserType enum
     * an error response.
     */
    public function getUsersTypes()
    {
        try {
            // Retrieve the user types from the UserType enum
            $usersTypes = UserType::getKeys();

            // Return a successful API response with the user types
            return api_response(data: $usersTypes, message: 'Successfully getting users types', code: 200);
        } catch (Exception $e) {
            // Return an error response if an exception occurs
            return api_response(
                errors: [$e->getMessage()],
                message: 'getting users types error',
                code: 500
            );
        }
    }

    /**
     * Delete a user.
     * @param User $user User to delete.
     * @return JsonResponse API response confirming the deletion of the user.
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-11
     */
    public function destroy(User $user)
    {
        try {
            if (file_exists($user->profile->avatar)) {
                if (!in_array($user->profile->avatar, ['/images/profile_images/man', '/images/profile_images/woman']))
                    unlink(public_path($user->profile->avatar));
            }
            // Delete the user
            $user->delete();

            // Return API response confirming the deletion
            return api_response('Successfully deleting user', 200);
        } catch (Exception $e) {
            // Return error response if an exception occurs
            return api_response(errors: [$e->getMessage()], message: 'deleting user error', code: 500);
        }
    }
}

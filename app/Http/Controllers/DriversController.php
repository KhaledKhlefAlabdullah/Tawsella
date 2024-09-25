<?php

namespace App\Http\Controllers;

use App\Enums\UserEnums\DriverState;
use App\Events\Movements\DriverChangeStateEvent;
use App\Http\Requests\DriverStateRequest;
use App\Http\Requests\UserRequests\UserRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DriversController extends Controller
{
    /**
     * Retrieve drivers details
     * @return JsonResponse
     */
    public function index()
    {
        try {
            $drivers = User::getDrivers(15); // 15 items per page

            return api_response(data: $drivers, message: 'Successfully getting drivers.');
        } catch (Exception $e) {
            return api_response(errors: [$e->getMessage()],message: 'Error in getting drivers data.', code: 500);
        }
    }

    /**
     * Create new driver
     * @param UserRequest $request
     * @return JsonResponse
     */
    public function store(UserRequest $request){
        return User::registerUser($request);
    }

    /**
     * Retrieve driver details
     * @param User $driver
     * @return JsonResponse
     */
    public function show(User $driver)
    {
        try {
            $driver = User::mappingSingleDriver($driver);

            return api_response(data:$driver);
        } catch (Exception $e) {
            return api_response(errors: [$e->getMessage()], message: 'Error in getting driver details.', code: 500);
        }
    }


    /**
     * Change driver state from received to ready or to in break
     * @param DriverStateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeDriverState(DriverStateRequest $request)
    {
        try {
            $validatedData = $request->validated();

            $driver = Auth::user();

            if ($validatedData['state'] == DriverState::InBreak) {
                $message = 'Driver is in_break';
            } elseif ($validatedData['state'] == DriverState::Ready) {
                $message = 'Driver is Ready';
            }

            $driver->state = $validatedData['state'];
            $driver->save();

            DriverChangeStateEvent::dispatch($driver);

            return api_response($message, 200);
        } catch (Exception $e) {
            return api_response(null, 'Failed to update driver state', 500, null, ['error' => [$e->getMessage()]]);
        }
    }

    /**
     * Delete driver
     * @param User $driver
     * @return JsonResponse
     */
    public function destroy(User $driver)
    {
        try {
            removeFile($driver->profile->avatar);
            $taxi = $driver->taxi;
            if ($taxi) {
                $taxi->update(['driver_id' => null]);
            }
            $driver->delete();
            return api_response(message: 'Successfully deleted driver.');
        } catch (Exception $e) {
            return api_response(errors: [$e->getMessage()], message: 'Error in deleting driver', code: 500);
        }
    }
}

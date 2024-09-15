<?php

namespace App\Http\Controllers;

use App\Enums\UserEnums\DriverState;
use App\Http\Requests\DriverStateRequest;
use App\Models\User;
use Exception;
use App\Models\Taxi;
use Illuminate\Support\Facades\Auth;

class DriversController extends Controller
{
    /**
     * Retrieve drivers details
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        try {
            $drivers = User::getDrivers(15); // 15 items per page

            return view('Driver.index', ['drivers' => $drivers]);
        } catch (Exception $e) {
            return redirect()->back()->withErrors('Error in getting drivers data.\n errors:' . $e->getMessage())->withInput();
        }
    }


    /**
     * Retrieve driver details
     * @param User $driver
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse|never
     */
    public function show(User $driver)
    {
        try {
            $driver = User::mappingSingleDriver($driver);

            return view('Driver.show', ['driver' => $driver]);
        } catch (Exception $e) {
            return redirect()->back()->withErrors('Error in getting driver details.\n errors:'.$e->getMessage())->withInput();
        }
    }


    /**
     * Redirect to edit driver page
     * @param User $driver
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse
     */
    public function edit(User $driver)
    {
        try {

            $driver = User::mappingSingleDriver($driver);

            return view('Driver.show', ['driver' => $driver]);
        } catch (Exception $e) {
            return redirect()->back()->withErrors('There error in redirect to edit driver page.\n errors:'.$e->getMessage())->withInput();
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
            return api_response($message, 200);
        } catch (Exception $e) {
            return api_response(null, 'Failed to update driver state', 500, null, ['error' => $e->getMessage()]);
        }
    }

    /**
     * Delete driver
     * @param User $driver
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $driver)
    {
        try {

            $taxi = $driver->taxi;

            // If the driver has an associated taxi, nullify the driver_id
            if ($taxi) {
                $taxi->update(['driver_id' => null]);
            }

            $driver->delete();

            return redirect()->back()->with('success', 'Successfully deleted driver.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors('Error in deleting driver.\n errors:'.$e->getMessage())->withInput();
        }
    }
}

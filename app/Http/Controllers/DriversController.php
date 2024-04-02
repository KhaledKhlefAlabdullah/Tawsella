<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use App\Http\Requests\DriverStateRequest;
use App\Http\Requests\UpdateDriverRequest;
use App\Models\Calculations;
use App\Models\Taxi;
use App\Models\UserProfile;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class DriversController extends Controller
{
    public function index()
    {
        try {

            $drivers = $this->getDrivers(['users.user_type' => 'driver'],'get');

            $combinedAccounts = $this->getCalculations($drivers);

            return view('Driver.index', ['drivers' => $combinedAccounts]);
        } catch (Exception $e) {
            return redirect()->back()->withErrors('هنالك خطأ في جلب البياانت الرجاء المحاولة مؤة أخرى.\nالاخطاء:' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        try {
            // العثور على بيانات السائق باستخدام المعرف الممرر
            $driver = $this->getDrivers(['users.id' => $id,'users.user_type' => 'driver'],'first');
            // التحقق مما إذا كان السائق موجودًا
            if (!$driver) {
                return abort(404, 'Driver not found');
            }

            // إعادة عرض بيانات السائق
            return view('Driver.show', ['driver' => $driver]);
        } catch (Exception $e) {
            return redirect()->back()->withErrors('هنالك خطأ في جلب البياانت الرجاء المحاولة مؤة أخرى.\nالاخطاء:' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        try {

            $driver = $this->getDrivers(['users.id' => $id,'users.user_type' => 'driver'],'first');

            if (!$driver) {
                return redirect()->back()->withErrors(['driver is not exists'.'An error occurred. Please try again.'])->withInput();
            }
            return view('Driver.show', ['driver' => $driver]);
        } catch (Exception $e) {
            return redirect()->back()->withErrors('هنالك خطأ في جلب البياانت الرجاء المحاولة مؤة أخرى.\nالاخطاء:' . $e->getMessage())->withInput();
        }
    }

    public function setState(Request $request)
    {
        try {
            $driverId = auth()->id();

            // التحقق من وجود السائق في قاعدة البيانات
            $driver = User::where('id', $driverId)->where('user_type', 'driver')->first();
            if (!$driver) {
                return api_response(null, 'Driver not found', 404);
            }

            // تحديث حالة السائق بناءً على القيمة المرسلة في الطلب
            if ($request->state == 0) {
                $driver->driver_state = 'in_break';
                $message = 'Driver is in_break';
            } elseif ($request->state == 1) {
                $driver->driver_state = 'Ready';
                $message = 'Driver is Ready';
            } else {
                // إذا كانت القيمة المرسلة غير صالحة
                return api_response(null, 'Invalid state value', 400);
            }

            $driver->save();
            return api_response(null, $message, 200);
        } catch (Exception $e) {
            return api_response(null, 'Failed to update driver state', 500, null, ['error' => $e->getMessage()]);
        }
    }

    public function destroy(string $id)
    {
        try {
            $driver = getAndCheckModelById(User::class, $id);

            $driver->delete();

            return redirect()->back()->with('success', 'تم حذف السائق بنجاح');
        } catch (Exception $e) {
            return redirect()->back()->withErrors('هنالك خطأ في جلب البياانت الرجاء المحاولة مؤة أخرى.\nالاخطاء:' . $e->getMessage())->withInput();
        }
    }

    ///////////////////////////////// helper functions here /////////////////////////////////////

    /**
     * Get Drivers data
     */
    public function getDrivers(array $conditions, $method)
    {
        $query = User::select('user_profiles.name', 'users.email', 'user_profiles.phoneNumber', 'user_profiles.avatar', 'users.id', 'users.is_active', 'taxis.plate_number', 'taxis.lamp_number')
                    ->leftJoin('user_profiles', 'users.id', '=', 'user_profiles.user_id')
                    ->leftJoin('taxis', 'users.id', '=', 'taxis.driver_id')
                    ->leftJoin('calculations', 'users.id', '=', 'calculations.driver_id')
                    ->where('users.user_type', 'driver');
    
        // Apply the specified method
        if ($method === 'get') {
            return $query->get();
        } elseif ($method === 'first') {
            return $query->first(); // Change the limit value as needed
        } else {
            return $query->get(); // Default to getting all records if method is not recognized
        }
    }

        /**
     * Get Drivers calculations
     */
    public function getCalculations($drivers)
    {
        // Get today's date
        $today = Carbon::now()->toDateString();

        // Get driver accounts for today
        $todayAccounts = Calculations::select('driver_id', DB::raw('SUM(totalPrice) as total_today'))
            ->whereDate('created_at', $today)
            ->groupBy('driver_id')
            ->get();

        // Get total account for each driver
        $totalAccounts = Calculations::select('driver_id', DB::raw('SUM(totalPrice) as total_previous'))
            ->groupBy('driver_id')
            ->get();

        // Combine today's and previous accounts
        $combinedAccounts = [];
        foreach ($drivers as $driver) {
            $driver_id = $driver->id;
            $todayTotal = $todayAccounts->firstWhere('driver_id', $driver_id)->total_today ?? 0;
            $previousTotal = $totalAccounts->firstWhere('driver_id', $driver_id)->total_previous ?? 0;

            $combinedAccounts[] = (object)[
                'driver_id' => $driver_id,
                'name' => $driver->name,
                'email' => $driver->email,
                'phoneNumber' => $driver->phoneNumber,
                'total_today' => $todayTotal,
                'total_previous' => $previousTotal,
                'is_active' => $driver->is_active,
                'plate_number' => $driver->plate_number,
                'lamp_number' => $driver->lamp_number,
            ];
        }

        return $combinedAccounts;
    }

}

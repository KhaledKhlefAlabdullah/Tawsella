<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use App\Http\Requests\DriverStateRequest;
class DriversController extends Controller
{
    public function index()
    {
        try {
            $drivers = User::select('user_profiles.name', 'user_profiles.phoneNumber', 'users.id', 'users.email', 'taxis.plate_number')
                ->leftJoin('user_profiles', 'users.id', '=', 'user_profiles.user_id')
                ->leftJoin('taxis', 'users.id', '=', 'taxis.driver_id')
                ->where('users.user_type', 'driver')->get();
            return view('Driver.index', ['drivers' => $drivers]);
        } catch (Exception $e) {
            return abort(500, 'there error in getting the drivers data');
        }
    }
    public function show($id)
    {
        try {
            // العثور على بيانات السائق باستخدام المعرف الممرر
            $driver = User::select('user_profiles.name', 'user_profiles.phoneNumber', 'users.id', 'users.email', 'taxis.plate_number')
                ->leftJoin('user_profiles', 'users.id', '=', 'user_profiles.user_id')
                ->leftJoin('taxis', 'users.id', '=', 'taxis.driver_id')
                ->where('users.user_type', 'driver')
                ->where('users.id', $id)
                ->first();
            // التحقق مما إذا كان السائق موجودًا
            if (!$driver) {
                return abort(404, 'Driver not found');
            }

            // إعادة عرض بيانات السائق
            return view('Driver.show', ['driver' => $driver]);
        } catch (Exception $e) {
            return abort(500, 'There was an error in getting the driver data');
        }
    }

    public function edit($id)
    {
        try {
            $driver = User::select('user_profiles.name', 'user_profiles.phoneNumber', 'users.id', 'users.email', 'taxis.plate_number')
                ->leftJoin('user_profiles', 'users.id', '=', 'user_profiles.user_id')
                ->leftJoin('taxis', 'users.id', '=', 'taxis.driver_id')
                ->where('users.user_type', 'driver')
                ->where('users.id', $id)
                ->first();
            if (!$driver) {
                return abort(404, 'Driver not found');
            }
            return view('Driver.show', ['driver' => $driver]);
        } catch (Exception $e) {
            return abort(500, 'There was an error in getting the driver data');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $driver = User::find($id);
            if (!$driver) {
                return abort(404, 'Driver not found');
            }
            $driver->update([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'plate_number' => $request->input('plate_number'),
                'phoneNumber' => $request->input('phoneNumber'),
                // تحديث المزيد من الحقول هنا حسب الحاجة
            ]);
            return redirect()->route('drivers.show', $driver->id)->with('success', 'Driver updated successfully');
        } catch (Exception $e) {
            return abort(500, 'There was an error in updating the driver data');
        }
    }public function setState(DriverStateRequest $request)
    {
        try {

            // الحصول على معرف السائق من الـ Request بالفعل
            $driverId = $request->driver_id;

            // التحقق من وجود السائق في قاعدة البيانات
            $driver = User::where('id', $driverId)->where('user_type', 'driver')->first();

            if (!$driver) {
                return response()->json(['message' => 'Driver not found'], 404);
            }

            // تحديث حالة السائق بناءً على القيمة المرسلة في الطلب
            if ($request->state == 0) {
                $driver->driver_state = 'in_break';
            } elseif ($request->state == 1) {
                $driver->driver_state = 'Ready';
            }
            $driver->save();

            return response()->json(['message' => 'Driver state updated successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to update driver state', 'error' => $e->getMessage()], 500);
        }
    }
}

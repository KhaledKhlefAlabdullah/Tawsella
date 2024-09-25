<?php

namespace App\Http\Controllers;

use App\Enums\UserEnums\UserType;
use App\Http\Requests\CalculationRequest;
use App\Models\Calculation;
use App\Models\TaxiMovement;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class CalculationController extends Controller
{

    /**
     * View list of calculations.
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-
     * @return JsonResponse to view page
     */
    public function index()
    {
        try {
            $drivers = User::role(UserType::TaxiDriver()->key)->paginate(10);

            return api_response(data: [
                'calculations' => Calculation::mappingDriversCalculations($drivers),
                'drivers' => $drivers
            ], message: 'Successfully getting drivers and calculations');
        } catch (Exception $e) {
            return api_response(errors: [$e->getMessage()], message: 'Error in getting drivers with calculations', code: 500);
        }
    }


    /**
     * Display the specified resource.
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-
     * @return JsonResponse to view page
     */
    public function show(User $driver)
    {
        try {
            $driverMovements = count_items(TaxiMovement::class,['driver_id' => $driver->id, 'is_completed' => true]);
            $totalMount = Calculation::totalAccounts($driver->id);
            $totalDistance = Calculation::where('driver_id', $driver->id)->sum('distance');
            $movements = Calculation::driverMovementsCalculations($driver->id);
            $details = [
                'driverMovements' => $driverMovements,
                'totalMount' => $totalMount,
                'totalDistance' => $totalDistance
            ];

            return api_response(data: ['details' => $details, 'movements' => $movements], message: 'Successfully getting driver movements');
        } catch (Exception $e) {
            return api_response(errors: [$e->getMessage()],message: 'هنالك خطأ في جلب البيانات الرجاء المحاولة مرة أخرى.', code: 500);
        }
    }

    /**
     * Bring The mounts
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-
     * @return JsonResponse to view page
     */
    public function bring(User $driver)
    {
        try {
            $bringCount = $driver->calculations()->where('is_bring', false)->count();

            if($bringCount == 0) {
                return api_response('drivers.index')->with('success', 'The driver has no outstanding payments to bring.');
            }

            $driver->calculations()->where('is_bring', false)
                ->update(['is_bring' => true]);

            return api_response(message: 'The outstanding payments have been successfully marked as brought.');
        } catch (Exception $e) {
            return api_response(errors: [$e->getMessage()],message: 'Error bringing payments. Please try again.', code: 500);
        }
    }



    /**
     * Remove the specified resource from storage.
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-
     * @return JsonResponse to view page
     */
    public function destroy(Calculation $calculation)
    {
        try {
            $calculation->delete();

            return api_response(message: 'Successfully calculation deleted.');
        } catch (Exception $e) {
            return api_response(errors: [$e->getMessage()],message: 'Error in deleted calculation.', code: 500);
        }
    }
}

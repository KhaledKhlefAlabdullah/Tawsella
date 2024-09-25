<?php

namespace App\Http\Controllers;

use App\Enums\UserEnums\UserType;
use App\Http\Requests\CalculationRequest;
use App\Models\Calculation;
use App\Models\TaxiMovement;
use App\Models\User;
use App\Services\PaginationService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class CalculationController extends Controller
{
    protected $paginationService;

    public function __construct(PaginationService $paginationService)
    {
        $this->paginationService = $paginationService;
    }

    /**
     * View list of calculations.
     * @return JsonResponse to view page
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-
     */
    public function index(Request $request)
    {
        $query = User::query()->role(UserType::TaxiDriver()->key);
        $drivers = $this->paginationService->applyPagination($query, $request);
        return api_response(
            data: Calculation::mappingDriversCalculations($drivers->items()),
            message: 'Successfully getting drivers and calculations',
            pagination: get_pagination($drivers, $request));
    }


    /**
     * Display the specified resource.
     * @return JsonResponse to view page
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-
     */
    public function show(User $driver)
    {
        try {
            $driverMovements = count_items(TaxiMovement::class, ['driver_id' => $driver->id, 'is_completed' => true]);
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
            return api_response(errors: [$e->getMessage()], message: 'هنالك خطأ في جلب البيانات الرجاء المحاولة مرة أخرى.', code: 500);
        }
    }

    /**
     * Bring The mounts
     * @return JsonResponse to view page
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-
     */
    public function bring(User $driver)
    {
        try {
            $bringCount = $driver->calculations()->where('is_bring', false)->count();

            if ($bringCount == 0) {
                return api_response('drivers.index')->with('success', 'The driver has no outstanding payments to bring.');
            }

            $driver->calculations()->where('is_bring', false)
                ->update(['is_bring' => true]);

            return api_response(message: 'The outstanding payments have been successfully marked as brought.');
        } catch (Exception $e) {
            return api_response(errors: [$e->getMessage()], message: 'Error bringing payments. Please try again.', code: 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     * @return JsonResponse to view page
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-
     */
    public function destroy(Calculation $calculation)
    {
        try {
            $calculation->delete();

            return api_response(message: 'Successfully calculation deleted.');
        } catch (Exception $e) {
            return api_response(errors: [$e->getMessage()], message: 'Error in deleted calculation.', code: 500);
        }
    }
}

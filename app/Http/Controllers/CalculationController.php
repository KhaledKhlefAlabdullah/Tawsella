<?php

namespace App\Http\Controllers;

use App\Enums\UserEnums\UserType;
use App\Models\Calculation;
use App\Models\TaxiMovement;
use App\Models\User;
use App\Services\PaginationService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


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
            message: 'تم جلب معلومات الحسابات المالية للسائقين',
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

            return api_response(data: ['details' => $details, 'movements' => $movements], message: 'تم جلب معلومات طلبات السائقين');
        } catch (Exception $e) {
            return api_response(message: 'هناك خطأ في جلب معلومات طلبات السائقين الرجاء المحاولة مرة أخرى', code: 500, errors: [$e->getMessage()]);
        }
    }

    /**
     * Bring The mounts
     * @return JsonResponse to view page
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-
     */
    public function bring(User $driver): JsonResponse
    {
        try {
            $bringCount = $driver->calculations()->where('is_bring', false)->count();

            if ($bringCount == 0) {
                return api_response(message: 'السائق ليس لديه أي مبالغ غير مدفوعة بعد');
            }
            DB::beginTransaction();
            $driver->calculations()->where('is_bring', false)
                ->update(['is_bring' => true]);
            DB::commit();
            return api_response(message: 'تم استلام المبلغ من السائق بنجاح');
        } catch (Exception $e) {
            DB::rollBack();
            return api_response(message: 'هناك خطأ في استلام المبلغ من السائق الرجاء المحاولة مرة أخرى', code: 500, errors: [$e->getMessage()]);
        }
    }


    /**
     * Remove the specified resource from storage.
     * @return JsonResponse to view page
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-
     */
    public function  destroy(Calculation $calculation): JsonResponse
    {
        try {
            $calculation->delete();

            return api_response(message: 'تم حذف الحسابات بنجاح');
        } catch (Exception $e) {
            return api_response(message: 'هناك خطأ في حذف الحساب بنجاح', code: 500, errors: [$e->getMessage()]);
        }
    }
}

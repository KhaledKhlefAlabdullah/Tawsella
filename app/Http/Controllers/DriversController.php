<?php

namespace App\Http\Controllers;

use App\Enums\UserEnums\DriverState;
use App\Enums\UserEnums\UserType;
use App\Events\Movements\DriverChangeStateEvent;
use App\Http\Requests\DriverStateRequest;
use App\Http\Requests\UserRequests\UpdateDriverRequest;
use App\Http\Requests\UserRequests\UserRequest;
use App\Models\User;
use App\Services\PaginationService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DriversController extends Controller
{
    protected $paginationService;

    public function __construct(PaginationService $paginationService)
    {
        $this->paginationService = $paginationService;
    }


    /**
     * Retrieve drivers details
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $query = User::query()->with(['taxi', 'profile'])
            ->role(UserType::TaxiDriver()->key);

        $drivers = $this->paginationService->applyPagination($query, $request);

        $mappedDrivers = User::mappingDrivers($drivers->items());
        return api_response(data: $mappedDrivers, message: 'تم جلب بيانات السائقين بنجاح', pagination: get_pagination($drivers, $request));
    }

    /**
     * Get ready drivers
     * @return JsonResponse
     */
    public function getReadiesDrivers()
    {
        $readyDrivers = User::getReadyDrivers();

        return api_response(data: $readyDrivers, message: 'تم جلب بيانات السائقين المتاحين بنجاح');
    }

    /**
     * Create new driver
     * @param UserRequest $request
     * @return JsonResponse
     */
    public function store(UserRequest $request)
    {
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

            return api_response(data: $driver, message: 'تم جلب بيانات السائق بنجاح');
        } catch (Exception $e) {
            return api_response(message: 'هناك خطأ في جلب بيانات السائق', code: 500, errors: [$e->getMessage()]);
        }
    }

    /**
     * Update driver details
     * @param UserRequest $request
     * @param User $driver
     * @return JsonResponse
     */
    public function update(UpdateDriverRequest $request, User $driver)
    {
        try {
            DB::beginTransaction();
            $validatedData = $request->validated();

            User::handelUpdateDetails($driver, $validatedData);

            DB::commit();
            return api_response(data: User::mappingSingleDriver($driver), message: 'تم تحديث الملف الشخصي بنجاح');
        } catch (Exception $e) {
            DB::rollBack();
            return api_response(message: 'هناك خطأ في تحديث الملف الشخصي', code: 500, errors: [$e->getMessage()]);
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
                $message = 'السائق في استراحة';
                $state = 'في استراحة';
            } elseif ($validatedData['state'] == DriverState::Ready) {
                $message = 'السائق مستعد';
                $state = 'جاهز';
            }

            $driver->driver_state = $validatedData['state'];
            $driver->save();
            $notification = [
                'title' => $message.'!',
                'body' => [
                    'message' => 'السائق '.$state.' الأن',
                    'driver' => $driver->profile
                ]
            ];
            DriverChangeStateEvent::dispatch($driver, $notification);
            $admin = User::find(getAdminId());
            send_notifications($admin, $notification);
            return api_response($message);
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
            removeFile($driver->profile?->avatar);
            $taxi = $driver->taxi;
            if ($taxi) {
                $taxi->update(['driver_id' => null]);
            }
            $driver->delete();
            return api_response(message: 'تم حذف السائق بنجاح');
        } catch (Exception $e) {
            return api_response(message: 'هناك خطأ في حذف السائق', code: 500, errors: [$e->getMessage()]);
        }
    }
}

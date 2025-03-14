<?php

namespace App\Http\Controllers;

use App\Enums\MovementRequestStatus;
use App\Events\Locations\GetTaxiLocationsEvent;
use App\Http\Requests\Taxis\GetTaxiLocationRequest;
use App\Http\Requests\Taxis\TaxiRequest;
use App\Models\Taxi;
use App\Models\User;
use App\Services\PaginationService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaxiController extends Controller
{

    protected $paginationService;

    public function __construct(PaginationService $paginationService)
    {
        $this->paginationService = $paginationService;
    }

    /**
     * Get taxis
     * @return JsonResponse
     * @author Khaled <khaledabdullah2001104@gmail.com>
     *
     */
    public function index(Request $request)
    {
        $query = Taxi::query()->with('driver.profile');

        $taxisDetails = $this->paginationService->applyPagination($query, $request);

        $taxis = Taxi::mappingTaxis($taxisDetails->items());
        return api_response(data: $taxis, message: 'تم جلب بيانات السيارات بنجاح', pagination: get_pagination($taxisDetails, $request));
    }

    /**
     * Get the taxi location
     * @param GetTaxiLocationRequest $request
     * @param User $driver
     * @return \Illuminate\Http\JsonResponse
     * @author Khaled <khaledabdullah2001104@gmail.com>
     *
     */
    public function getTaxiLocation(GetTaxiLocationRequest $request, User $driver)
    {
        try {
            $validatedData = $request->validated();
            $taxi = $driver->taxi;
            if (!$taxi) {
                return api_response(message: 'السائق ليس لديه سيارة', code: 404);
            }
            $taxi->update([
                'last_location_latitude' => $validatedData['lat'],
                'last_location_longitude' => $validatedData['long']
            ]);

            $lifeMovementForDriver = $driver->driver_movements()->where(['is_completed' => false, 'is_canceled' => false])
                ->where('request_state', MovementRequestStatus::Accepted)->latest()->first();
            if ($lifeMovementForDriver) {
                // add point to path in taxi movements table to view it map
                $lifeMovementForDriver->addPointToPath($validatedData['lat'], $validatedData['long']);

                GetTaxiLocationsEvent::dispatch($taxi, json_decode($lifeMovementForDriver->path));
            }else{
                GetTaxiLocationsEvent::dispatch($taxi);
            }
            return api_response(message: 'تم تحديث موقع السيارة بنجاح');
        } catch (Exception $e) {
            return api_response(message: 'خطأ في تحديث موقع السيارة', code: 500, errors: [$e->getMessage()]);
        }
    }

    /**
     * View taxi location map life
     * @param Taxi $taxi
     * @return JsonResponse
     * @author Khaled <khaledabdullah2001104@gmail.com>
     */
    public function viewLifeMap(Taxi $taxi)
    {
        $data = [
            'lat' => $taxi->last_location_latitude,
            'driver_id' => $taxi->driver_id,
            'long' => $taxi->last_location_longitude,
            'name' => $taxi->driver?->profile?->name,
        ];

        return api_response(data: $data, message: 'تم جلب بيانات الخريطة الحية بنجاح');
    }

    /**
     * Store a newly created resource in storage.
     * @param TaxiRequest $request
     * @return JsonResponse
     * @author Khaled <khaledabdullah2001104@gmail.com>
     */
    public function store(TaxiRequest $request)
    {
        try {
            $validatedData = $request->validated();
            $taxi = Taxi::create($validatedData);
            return api_response(data: $taxi, message: 'تم اضافة سيارة بنجاح');
        } catch (Exception $e) {
            return api_response(message: 'هناك خطأ في اضافة سيارة', code: 500, errors: [$e->getMessage()]);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param TaxiRequest $request
     * @param Taxi $taxi
     * @return JsonResponse
     * @author Khaled <khaledabdullah2001104@gmail.com>
     */
    public function update(TaxiRequest $request, Taxi $taxi)
    {
        try {
            $validatedData = $request->validated();
            $taxi->update($validatedData);
            return api_response(data: $taxi, message: 'Successfully updated taxi');
        } catch (Exception $e) {
            return api_response(message: 'Error in updating taxi', code: 500, errors: [$e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param Taxi $taxi
     * @return JsonResponse
     */
    public function destroy(Taxi $taxi)
    {
        try {
            $taxi->delete();
            return api_response(message: 'Successfully deleted taxi');
        } catch (Exception $e) {
            return api_response(message: 'Error in deleting taxi', code: 500, errors: [$e->getMessage()]);
        }
    }
}

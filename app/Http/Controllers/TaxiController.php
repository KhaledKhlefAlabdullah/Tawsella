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
        $query = $this->paginationService->applyFilters($query, $request);
        $query = $this->paginationService->applySorting($query, $request);
        $taxisDetails = $this->paginationService->paginate($query, $request);

        $taxis = Taxi::mappingTaxis($taxisDetails);
        return api_response(data: $taxis, pagination:  get_pagination($taxisDetails, $request), message: 'Successfully retrieved taxis');
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
            $taxi->update([
                'last_location_latitude' => $validatedData['lat'],
                'last_location_longitude' => $validatedData['long']
            ]);
            $lifeMovementForDriver = $driver->driver_movements()->where(['is_completed' => false, 'is_canceled' => false])
                ->where('request_state', MovementRequestStatus::Accepted)->first();
            $receiver = $lifeMovementForDriver->customer();
            GetTaxiLocationsEvent::dispatch($receiver, $taxi);
            return api_response(message: 'Successfully updating taxi locations.');
        } catch (Exception $e) {
            return api_response(errors: [$e->getMessage()], message: 'Error in updating taxi location', code: 500);
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
            'name' => $taxi->driver()->profile->name,
        ];
        return api_response(data: $data, message: 'Successfully retrieving life map');
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
            return api_response(data: $taxi, message: 'Successfully created taxi');
        } catch (Exception $e) {
            return api_response(errors: [$e->getMessage()], message: 'Error in creating taxi', code: 500);
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
            return api_response(errors: [$e->getMessage()], message: 'Error in updating taxi', code: 500);
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
            return api_response(errors: [$e->getMessage()], message: 'Error in deleting taxi', code: 500);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Enums\MovementRequestStatus;
use App\Events\Locations\GetTaxiLocationsEvent;
use App\Http\Requests\Taxis\GetTaxiLocationRequest;
use App\Http\Requests\Taxis\TaxiRequest;
use App\Models\Taxi;
use App\Models\User;
use Exception;

class TaxiController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        $taxisDetails = Taxi::with('driver.profile')->get();

        $taxis = Taxi::mappingTaxis($taxisDetails);

        return view('taxis.index', compact('taxis'));
    }

    /**
     * Get the taxi location
     * @param GetTaxiLocationRequest $request
     * @param string $driver_id
     * @return \Illuminate\Http\JsonResponse
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

            $receiver = $lifeMovementForDriver->customer ?? null;

            GetTaxiLocationsEvent::dispatch($receiver, $taxi);

            return api_response(message: 'Successfully updating taxi locations.');
        } catch (Exception $e) {
            return api_response(errors: $e->getMessage(), message: 'Error in updating taxi location', code: 500);
        }
    }

    /**
     * View taxi location map life
     * @param Taxi $taxi
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function viewLifeMap(Taxi $taxi)
    {
        $data = [
            'lat' => $taxi->last_location_latitude,
            'driver_id' => $taxi->driver_id,
            'long' => $taxi->last_location_longitude,
            'name' => $taxi->driver()->profile->name,
        ];
        return view('taxi_movement.map', ['data' => $data])->with('success', __('success-view-map'));
    }

    /**
     * Show the form for creating a new resource.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse
     */
    public function create()
    {
        $drivers = User::getDriversDontHaveTaxi();

        return view('taxis.create', compact('drivers'));
    }

    /**
     * Store a newly created resource in storage.
     * @param TaxiRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(TaxiRequest $request)
    {
        try {
            $validatedData = $request->validated();

            Taxi::create($validatedData);

            return redirect()->back()->with('success', __('taxi-created-success'));
        } catch (Exception $e) {
            return redirect()->back()->withErrors(__('taxi-created-error') . "\n errors:" . $e->getMessage())->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param Taxi $taxi
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse
     */
    public function edit(Taxi $taxi)
    {
        $drivers = User::getDriversDontHaveTaxi();

        return view('taxis.edit', compact('taxi', 'drivers'));
    }

    /**
     * Update the specified resource in storage.
     * @param TaxiRequest $request
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(TaxiRequest $request, Taxi $taxi)
    {
        try {
            $validatedData = $request->validated();

            $taxi->update($validatedData);

            return redirect()->route('taxis.index')->with('success', __('taxi-updated-success'));
        } catch (Exception $e) {
            return redirect()->back()->withErrors(__('taxi-updated-success'). "\n errors:" . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param Taxi $taxi
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Taxi $taxi)
    {
        try {

            $taxi->delete();

            return redirect()->route('taxis.index')->with('success', '');
        } catch (Exception $e) {
            return redirect()->back()->withErrors(__('taxi-deleting-error') . "\n errors:" . $e->getMessage())->withInput();
        }
    }
}

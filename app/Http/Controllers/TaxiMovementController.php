<?php

namespace App\Http\Controllers;

use App\Events\CreateTaxiMovementEvent;
use App\Http\Requests\TaxiMovementRequest;
use App\Models\TaxiMovement;
use Exception;
use Illuminate\Http\Request;

class TaxiMovementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
        } catch (Exception $e) {
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaxiMovementRequest $request)
    {
        try {

            $validatedData = $request->validated();

            TaxiMovement::create($validatedData);

            // 1
            // event(new CreateTaxiMovementEvent($request->input('customer_id'),
            // $request->input('start_latitude'),
            // $request->input('start_longitude')));
            
            // 2
            CreateTaxiMovementEvent::dispatch(
                $request->input('customer_id'),
                $request->input('start_latitude'),
                $request->input('start_longitude')
            );

            return api_response(message: 'create-movement-success');
        } catch (Exception $e) {
            return api_response(errors: [$e->getMessage()], message: 'create-movement-error', code: 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(TaxiMovement $taxiMovement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TaxiMovement $taxiMovement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TaxiMovement $taxiMovement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TaxiMovement $taxiMovement)
    {
        try {

            $taxiMovement->delete();

            return redirect()->back();
        } catch (Exception $e) {
            return abort('there error in deleting this movemnt', 500);
        }
    }
}

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
    public function currentTaxiMovement()
    {
        try{

            $taxiMovement = TaxiMovement::select('taxi_movements.my_address','taxi_movements.destnation_address','taxi_movements.gender','taxi_movements.start_latitude','taxi_movements.start_longitude','driver.email as driver_email','customer.email as customer_email','driver_profile.name as driver_name','driver_profile.phoneNumber as driver_phone','customer_profile.name as customer_name','customer_profile.phoneNumber as customer_phone','taxis.car_name as car_car_name','taxis.lamp_number as car_lamp_number','taxis.plate_number as car_plate_number','taxi_movement_types.type','taxi_movement_types.price')
            ->leftJoin('users as driver','taxi_movements.driver_id','=','driver.id')
            ->leftJoin('users as customer','taxi_movements.customer_id','=','customer.id')
            ->leftJoin('user_profiles as driver_profile','taxi_movements.driver_id','=','driver_profile.user_id')
            ->leftJoin('user_profiles as customer_profile','taxi_movements.customer_id','=','customer_profile.user_id')
            ->leftJoin('taxis','taxi_movements.driver_id','=','taxis.id')
            ->leftJoin('taxi_movement_types','taxi_movements.movement_type_id','=','taxi_movement_types.id')
            ->where(['is_completed' => false,'is_canceled' => false])
            ->get();

            return view('taxi_movementcurrent_taxi_movement',['taxiMovement'=>$taxiMovement]);
        }
        catch(Exception $e){
            return abort(500,'there error in getting current taxi movement'.$e->getMessage());
        }
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
                $request->input('start_longitude'),
                $request->input('gender'),
                $request->input('my_address'),
                $request->input('destnation_address')

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
            return abort(500,'there error in deleting this movemnt');
        }
    }
}

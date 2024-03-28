<?php

namespace App\Http\Controllers;

use App\Events\AcceptTaxiMovemntEvent;
use App\Events\CreateTaxiMovementEvent;
use App\Events\MovementFindUnFindEvent;
use App\Events\RejectTaxiMovemntEvent;
use App\Http\Requests\TaxiMovementRequest;
use App\Models\Calculations;
use App\Models\Taxi;
use App\Models\TaxiMovement;
use App\Models\TaxiMovementType;
use App\Models\User;
use App\Models\UserProfile;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

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
        try {

            $currentDate = Carbon::now()->toDateString();

            // Query to get requests for the current day
            $taxiMovement = TaxiMovement::select('taxi_movements.my_address', 'taxi_movements.destnation_address', 'taxi_movements.gender', 'taxi_movements.start_latitude', 'taxi_movements.start_longitude', 'driver.email as driver_email', 'customer.email as customer_email', 'driver_profile.name as driver_name', 'driver_profile.phoneNumber as driver_phone', 'customer_profile.name as customer_name', 'customer_profile.phoneNumber as customer_phone', 'taxis.car_name as car_car_name', 'taxis.lamp_number as car_lamp_number', 'taxis.plate_number as car_plate_number', 'taxi_movement_types.type', 'taxi_movement_types.price')
                ->leftJoin('users as driver', 'taxi_movements.driver_id', '=', 'driver.id')
                ->leftJoin('users as customer', 'taxi_movements.customer_id', '=', 'customer.id')
                ->leftJoin('user_profiles as driver_profile', 'taxi_movements.driver_id', '=', 'driver_profile.user_id')
                ->leftJoin('user_profiles as customer_profile', 'taxi_movements.customer_id', '=', 'customer_profile.user_id')
                ->leftJoin('taxis', 'taxi_movements.driver_id', '=', 'taxis.id')
                ->leftJoin('taxi_movement_types', 'taxi_movements.movement_type_id', '=', 'taxi_movement_types.id')
                ->whereDate('taxi_movements.created_at', $currentDate)
                ->where(['taxi_movements.is_completed' => false, 'taxi_movements.is_canceled' => false, 'taxi_movements.request_state' => 'accepted'])
                ->get();

            return view('taxi_movement.currentTaxiMovement', ['taxiMovement' => $taxiMovement]);
        } catch (Exception $e) {
            return abort(500, 'there error in getting current taxi movement' . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaxiMovementRequest $request)
    {
        try {

            $validatedData = $request->validated();

            $taxiMovement = TaxiMovement::create($validatedData);

            // 1
            // event(new CreateTaxiMovementEvent($request->input('customer_id'),
            // $request->input('start_latitude'),
            // $request->input('start_longitude')));

            // 2
            CreateTaxiMovementEvent::dispatch(
                $taxiMovement
            );

            // $request->input('customer_id'),
            // $request->input('start_latitude'),
            // $request->input('start_longitude'),
            // $request->input('gender'),
            // $request->input('my_address'),
            // $request->input('destnation_address')

            return api_response(message: 'create-movement-success');
        } catch (Exception $e) {
            return api_response(errors: [$e->getMessage()], message: 'create-movement-error', code: 500);
        }
    }


    /**
     * Accept and regect Taxi movement request
     */
    public function accept_reject_request(Request $request, string $id)
    {
        try {
            $request->validate([
                'state' => 'sometimes|string|required|in:accepted,rejected',
                'driver_id' => 'sometimes|nullable|string',
                'message' => 'string|sometimes|nullable'
            ]);

            $taxiMovement = getAndCheckModelById(TaxiMovement::class, $id);

            $taxiMovement->update([
                'request_state' => $request->input('state'),
                'is_don' => true
            ]);

            if ($request->input('state') == 'accepted') {

                $driver_id = $request->input('driver_id');

                $driver = getAndCheckModelById(User::class, $driver_id);

                $driver->update([
                    'driver_state' => 'reserved'
                ]);

                $taxi_id = Taxi::where('driver_id', $driver_id)->first()->id;

                $taxiMovement->update([
                    'driver_id' => $driver_id,
                    'taxi_id' => $taxi_id
                ]);

                AcceptTaxiMovemntEvent::dispatch($taxiMovement);


            } else if($request->input('state') == 'rejected'){

                RejectTaxiMovemntEvent::dispatch(
                    $taxiMovement->customer_id,
                    $request->input('message')
                );
            }

            return redirect()->back()->with('success', 'Request ' . $request->input('state') . ' successfully.');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();

        } catch(Exception $e){
            return redirect()->back()->withErrors(['error' => $e->getMessage().'An error occurred. Please try again.'])->withInput();
        }
    }

    /**
     * Display the specified resource.
     */

    public function foundCustomer(Request $request, string $id)
    {
        $request->validate([
            'state' => 'required|boolean'
        ]);

        $taxiMovement = getAndCheckModelById(TaxiMovement::class, $id);

        $driver = UserProfile::where('user_id', $taxiMovement->driver_id)->first();
        $customer = UserProfile::where('user_id', $taxiMovement->customer_id)->first();

        $message = $request->input('state') ? 'تم ايجاد الزبون' : 'لم يتم العثور على الزبون';

        MovementFindUnFindEvent::dispatch(
            $driver->name ?? 'Unknown Driver',
            $customer->name ?? 'Unknown Customer',
            $message
        );

        if (!$request->input('state')) {
            // حذف taxiMovement
            $taxiMovement->delete();
        }

        return api_response(null, $message, 200);
    }


    /**
     * Make the movement is completed
     */
    public function makeMovementIsCompleted(Request $request, string $id)
    {
        try {

            $request->validate([
                'way' => 'sometimes|numeric',
                'end_lat' => 'required|numeric',
                'end_lon' => 'required|numeric'
            ]);

            $taxiMovement = getAndCheckModelById(TaxiMovement::class, $id);

            $taxiMovement->update([
                'is_completed' => true,
                'end_latitude' => $request->input('end_lat'),
                'end_longitude' => $request->input('end_lon')
            ]);

            $movement_type = TaxiMovementType::findOrFail($taxiMovement->movement_type_id);
            if ($movement_type->is_onKM) {
                $totalPrice = $request->input('way') * $movement_type->price;
            } else {
                $totalPrice = $movement_type->price;
            }

            Calculations::create([
                'driver_id' => Auth::id(),
                'taxi_movement_id' => $id,
                'totalPrice' => $totalPrice,
                'way' => $request->input('way')
            ]);

            $driverName = UserProfile::where('user_id', Auth::id())->first()->name;

            $customerName = UserProfile::where('user_id',  $taxiMovement->customer_id)->first()->name;

            $from = $taxiMovement->my_address;
            $to = $taxiMovement->destnation_address;

            MovementFindUnFindEvent::dispatch(
                $driverName,
                $customerName,
                'تم اكمال طلب الزبون من ' . $from . 'إلى ' . $to
            );
            return api_response(message: 'success');
        } catch (Exception $e) {
            return api_response(errors: $e->getMessage(), message: 'error', code: 500);
        }
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
            return abort(500, 'there error in deleting this movemnt');
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Events\AcceptTaxiMovemntEvent;
use App\Events\CreateTaxiMovementEvent;
use App\Events\MovementFindUnFindEvent;
use App\Events\RejectTaxiMovemntEvent;
use App\Http\Requests\TaxiMovementRequest;
use App\Models\Calculations;

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
     * Show the form for creating a new resource.
     */
    public function currentTaxiMovement()
    {
        $currentDate = Carbon::now()->format('Y-m-d');

        $taxiMovement = $this->get_data([
            'taxi_movements.id as movement_id',
            'taxi_movements.my_address',
            'taxi_movements.destnation_address',
            'taxi_movements.gender',
            'taxi_movements.start_latitude',
            'taxi_movements.start_longitude',
            'driver.email as driver_email',
            'customer.email as customer_email',
            'driver_profile.name as driver_name',
            'driver_profile.phoneNumber as driver_phone',
            'customer_profile.name as customer_name',
            'customer_profile.phoneNumber as customer_phone',
            'taxis.id as taxi_id',
            'taxis.car_name as car_car_name',
            'taxis.lamp_number as car_lamp_number',
            'taxis.plate_number as car_plate_number',
            'taxi_movement_types.type',
        ], ['taxi_movements.is_completed' => false, 'taxi_movements.is_canceled' => false, 'taxi_movements.request_state' => 'accepted'])
            ->whereDate('taxi_movements.created_at', $currentDate)
            ->get();

        return view('taxi_movement.currentTaxiMovement', ['taxiMovement' => $taxiMovement]);
    }


    // الدالة لعرض الطلبات المكتملة
    public function completedRequests()
    {

        // الحصول على الطلبات المكتملة من قاعدة البيانات
        $completedRequests = $this->get_data([
            'taxi_movements.id as movement_id',
            'taxi_movements.my_address',
            'taxi_movements.destnation_address',
            'taxi_movements.gender',
            'taxi_movements.start_latitude',
            'taxi_movements.start_longitude',
            'driver.email as driver_email',
            'customer.email as customer_email',
            'driver_profile.name as driver_name',
            'driver_profile.phoneNumber as driver_phone',
            'customer_profile.name as customer_name',
            'customer_profile.phoneNumber as customer_phone',
            'taxis.id as taxi_id',
            'taxis.car_name as car_car_name',
            'taxis.lamp_number as car_lamp_number',
            'taxis.plate_number as car_plate_number',
            'taxi_movement_types.type',
            'c.totalPrice as price',
            'taxi_movements.created_at as date',
        ], ['is_completed' => true])
            ->join('calculations as c', 'driver.id', '=', 'c.driver_id')
            ->get();
        // إعادة عرض النتائج في الواجهة
        return view('taxi_movement.completedRequests', ['completedRequests' => $completedRequests]);
    }

    /**
     * Get data by condations
     */
    public function get_data($columns, $condations)
    {
        try {

            // Query to get requests for the current day
            $data = TaxiMovement::select($columns)
                ->join('users as driver', 'taxi_movements.driver_id', '=', 'driver.id')
                ->join('users as customer', 'taxi_movements.customer_id', '=', 'customer.id')
                ->join('user_profiles as driver_profile', 'taxi_movements.driver_id', '=', 'driver_profile.user_id')
                ->join('user_profiles as customer_profile', 'taxi_movements.customer_id', '=', 'customer_profile.user_id')
                ->join('taxis', 'taxi_movements.taxi_id', '=', 'taxis.id')
                ->join('taxi_movement_types', 'taxi_movements.movement_type_id', '=', 'taxi_movement_types.id')
                ->distinct()
                ->where($condations);

            return $data;
        } catch (Exception $e) {
            return redirect()->back()->withErrors('هنالك خطأ في جلب البيانات الرجاء المحاولة مرة أخرى.\nالاخطاء:' . $e->getMessage())->withInput();
        }
    }

    /**
     * For View map for taxi location
     */
    public function view_map(string $selector, string $id)
    {
        try {

            if ($selector == 'taxi') {
               $data = ''; // Taxi::select('taxis.last_location_latitude as lat', 'taxis.driver_id', 'taxis.last_location_longitude as long', 'up.name')
                //     ->join('user_profiles as up', 'taxis.driver_id', '=', 'up.user_id')
                //     ->where('taxis.id', $id)->first();
            } else if ($selector == 'completed') {
                $data = TaxiMovement::select('taxi_movements.driver_id as driver_id', 'taxi_movements.end_latitude as lat', 'taxi_movements.end_longitude as long', 'up.name')
                    ->join('user_profiles as up', 'taxi_movements.customer_id', '=', 'up.user_id')
                    ->where('taxi_movements.id', $id)->first();

                return view('taxi_movement.map_completed', ['data' => $data])->with('success', 'تم عرض الخريطة بنحاح');
            }

            return view('taxi_movement.map', ['data' => $data])->with('success', 'تم عرض الخريطة بنحاح');
        } catch (Exception $e) {
            return redirect()->back()->withErrors('هنالك خطأ في جلب البيانات الرجاء المحاولة مرة أخرى.\nالاخطاء:' . $e->getMessage())->withInput();
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaxiMovementRequest $request)
    {
        try {

            $validatedData = $request->validated();

            // To check if the customer have request in last 4 menites dont create new one and return message
            $existsRequest = TaxiMovement::where('customer_id', $validatedData['customer_id'])
                ->where('created_at', '>=', Carbon::now()->subMinutes(10))
                ->latest()
                ->first();

            if ($existsRequest) {
                return api_response(message: 'لقد قمت بطلب سيارة قبل قليل انتظر قليلاً من فضلك ريثما يتم معالجة طلبك');
            }

            $taxiMovement = TaxiMovement::create($validatedData);

            // 1
            // event(new CreateTaxiMovementEvent($request->input('customer_id'),
            // $request->input('start_latitude'),
            // $request->input('start_longitude')));

            // 2
            CreateTaxiMovementEvent::dispatch(
                $taxiMovement
            );

            return api_response(message: 'تم انشاء الطلب بنجاح');
        } catch (Exception $e) {
            return api_response(errors: [$e->getMessage()], message: 'حدث خطأ اثناء انشاء الطلب', code: 500);
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
                'driver_id' => 'sometimes|nullable|string|exists:users,id',
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

                $taxi_id = '';//Taxi::where('driver_id', $driver_id)->first()->id;

                $taxiMovement->update([
                    'driver_id' => $driver_id,
                    'taxi_id' => $taxi_id
                ]);

                AcceptTaxiMovemntEvent::dispatch($taxiMovement);

                $message = 'قبول';
            } else if ($request->input('state') == 'rejected') {

                RejectTaxiMovemntEvent::dispatch(
                    $taxiMovement->customer_id,
                    $request->input('message')
                );

                $message = 'رفض';
            }

            return redirect()->back()->with('success', 'تم ' . $message . ' الطلب بنجاح');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (Exception $e) {
            return redirect()->back()->withErrors('هنالك خطأ في جلب البيانات الرجاء المحاولة مرة أخرى.\nالاخطاء:' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */

    public function foundCustomer(Request $request, string $id)
    {
        try {
            $request->validate([
                'state' => 'required|boolean'
            ]);

            $taxiMovement = getAndCheckModelById(TaxiMovement::class, $id);

            $driver = UserProfile::where('user_id', $taxiMovement->driver_id)->first();
            $customer = UserProfile::where('user_id', $taxiMovement->customer_id)->first();

            $d_name = $driver->name;
            $c_name = $customer->name;

            $message = $request->input('state') ? ' السائق ' . $d_name . ' وجد العميل ' . $c_name : ' السائق' . $d_name . ' لم يعثر على العميل ' . $c_name;

            MovementFindUnFindEvent::dispatch(
                $d_name ?? 'Unknown Driver',
                $c_name ?? 'Unknown Customer',
                $message
            );

            if (!$request->input('state')) {
                // حذف taxiMovement
                $taxiMovement->delete();
            }

            return api_response(message: $message);
        } catch (Exception $e) {
            return api_response(errors: $e->getMessage(), message: 'حدث خطأ في ايجاد او عدم ايجاد الزبون', code: 500);
        }
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

            $Calculation = Calculations::create([
                'driver_id' => Auth::id(),
                'taxi_movement_id' => $id,
                'totalPrice' => $totalPrice,
                'way' => $request->input('way')
            ]);

            getAndCheckModelById(User::class, Auth::id())->update([
                'driver_state' => 'ready'
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

            return api_response(data: $Calculation->totalPrice, message: 'success');
        } catch (Exception $e) {
            return api_response(errors: $e->getMessage(), message: 'error', code: 500);
        }
    }

    /**
     * Send Taxi movemnt request details
     */
    public function get_request_data(string $driver_id)
    {
        try {

            $request = TaxiMovement::select(
                'taxi_movements.id as request_id',
                'up.name',
                'up.phoneNumber',
                'taxi_movements.my_address as customer_address',
                'taxi_movements.destnation_address as destnation_address',
                'taxi_movements.gender as gender',
                'taxi_movements.start_latitude as location_lat',
                'taxi_movements.start_longitude as location_long',
                'tmt.type',
                'tmt.price',
                'tmt.is_onKM'
            )
                ->join('user_profiles as up', 'taxi_movements.customer_id', '=', 'up.user_id')
                ->join('taxi_movement_types as tmt', 'taxi_movements.movement_type_id', '=', 'tmt.id')
                ->where(['taxi_movements.driver_id' => $driver_id, 'is_completed' => false, 'is_canceled' => false, 'is_don' => true])
                ->whereDate('taxi_movements.created_at', today())
                ->first();
            if ($request)
                return api_response(data: $request, message: 'نجح في الحول على بيانات');
            return api_response(message: 'there now data');
        } catch (Exception $e) {
            return api_response(errors: $e->getMessage(), message: 'حدث خطأ في الحصول على بيانات', code: 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TaxiMovement $taxiMovement)
    {
        try {

            $taxiMovement->delete();

            return redirect()->back()->with('success', 'تم حذف الطلب بنجاح');
        } catch (Exception $e) {
            return redirect()->back()->withErrors('هنالك خطأ في جلب البيانات الرجاء المحاولة مرة أخرى.\nالاخطاء:' . $e->getMessage())->withInput();
        }
    }
}

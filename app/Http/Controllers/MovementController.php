<?php

namespace App\Http\Controllers;

use App\Enums\DriverState;
use App\Enums\UserType;
use App\Events\Movements\AcceptTransportationServiceRequestEvent;
use App\Events\Movements\RequestingTransportationServiceEvent;
use App\Http\Requests\MovementRequest;
use App\Http\Requests\NearestDriverRequest;
use App\Models\Movement;
use App\Models\User;
use App\Models\UserProfile;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class MovementController extends Controller
{

    /**
     * View list of movements
     * @return JsonResponse all Movements and paths
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-44
     */
    public function index()
    {
        // Fetch paginated movements with related customer and driver profiles
        $movements = Movement::with(['customer.profile', 'driver.profile'])->paginate(10);

        // Map the movements
        $mappedMovements = Movement::mappingMovements(collect($movements->items()));

        // Return the API response with paginated data and metadata
        return api_response(
            data: $mappedMovements,
            message: 'Successfully getting movements details',
            pagination: $movements,
        );
    }

    /**
     * View list of my movements
     * @return JsonResponse all Movements and paths
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-45
     */
    public function myMovements()
    {
        $movements = Movement::with(['customer.profile', 'driver.profile'])->where('driver_id', getMyId())->orWhere('customer_id', getMyId())->paginate(10);
        return api_response(data: Movement::mappingMovements($movements), message: 'Successfully getting movements details', pagination: $movements);
    }

    /**
     * View nearest drivers on my location
     * @return JsonResponse all Movements and paths
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-46
     */
    public function nearestDrivers(NearestDriverRequest $request)
    {
        $validatedData = $request->validated();

        $drivers = User::nearLocation($validatedData['latitude'], $validatedData['longitude'])
            ->with('profile') // Eager load profile relationship
            ->whereNotIn('user_type', [UserType::ADMIN(), UserType::CUSTOMER()])
            ->where(['user_type' => $validatedData['movement_type'], 'driver_state' => DriverState::Ready()])
            ->where('is_active', true)
            ->get();

        if(empty($drivers)){
            return api_response(message: 'We apologize, there is no driver nearest to you at the moment', code: 204);
        }
        return api_response(data: User::mappingNearestDrivers($drivers), message: 'Successfully getting nearest drivers');
    }

    /**
     * View list movements types
     * @return JsonResponse all Movements and paths
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-46
     */
    public function movementsTypes()
    {
        $movementTypes = UserType::getServicesTypes();
        return api_response(data: $movementTypes, message: 'Successfully getting movements types');
    }

    /**
     * Create movements request
     * @return JsonResponse all Movements and paths
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-46
     */
    public function store(MovementRequest $request)
    {
        try {

            $validatedData = $request->validated();

            if(getAndCheckModelById(User::class, $validatedData['driver_id'])->driver_stet != DriverState::Ready){
                return api_response(
                    message: 'This driver is currently unavailable. Please try another driver.',
                    code: 409
                );
            }

            // To check if the customer have request in last 4 mentees don't create new one and return message
            $existsRequest = Movement::where('customer_id', $validatedData['customer_id'])
                ->where('created_at', '>=', Carbon::now()->subMinutes(10))
                ->latest()
                ->first();

            if ($existsRequest) {
                return api_response(
                    message: 'You have recently requested a car. Please wait a moment while your request is being processed.',
                    code: 429
                );
            }

            $movement = Movement::create($validatedData);

            event(new RequestingTransportationServiceEvent($movement));

            return api_response(message: 'Successfully creating movement');
        } catch (Exception $e) {
            return api_response(errors: [$e->getMessage()], message: 'Creating movements error', code: 500);
        }
    }


    /**
     * Accept and reject Taxi movement request
     */
    public function accept_reject_request(Request $request, string $id)
    {
        try {
            $request->validate([
                'state' => 'sometimes|string|required|in:accepted,rejected',
                'driver_id' => 'sometimes|nullable|string|exists:users,id',
                'message' => 'string|sometimes|nullable'
            ]);

            $Movement = getAndCheckModelById(Movement::class, $id);

            $Movement->update([
                'request_state' => $request->input('state'),
                'is_don' => true
            ]);

            if ($request->input('state') == 'accepted') {

                $driver_id = $request->input('driver_id');

                $driver = getAndCheckModelById(User::class, $driver_id);

                $driver->update([
                    'driver_state' => 'reserved'
                ]);

                $taxi_id = ''; //Taxi::where('driver_id', $driver_id)->first()->id;

                $Movement->update([
                    'driver_id' => $driver_id,
                    'taxi_id' => $taxi_id
                ]);

                AcceptTransportationServiceRequestEvent::dispatch($Movement);

                $message = 'قبول';
            } else if ($request->input('state') == 'rejected') {

                RejectTaxiMovemntEvent::dispatch(
                    $Movement->customer_id,
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

            $Movement = getAndCheckModelById(Movement::class, $id);

            $driver = UserProfile::where('user_id', $Movement->driver_id)->first();
            $customer = UserProfile::where('user_id', $Movement->customer_id)->first();

            $d_name = $driver->name;
            $c_name = $customer->name;

            $message = $request->input('state') ? ' السائق ' . $d_name . ' وجد العميل ' . $c_name : ' السائق' . $d_name . ' لم يعثر على العميل ' . $c_name;

            MovementFindUnFindEvent::dispatch(
                $d_name ?? 'Unknown Driver',
                $c_name ?? 'Unknown Customer',
                $message
            );

            if (!$request->input('state')) {
                // حذف Movement
                $Movement->delete();
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

            // $request->validate([
            //     'way' => 'sometimes|numeric',
            //     'end_lat' => 'required|numeric',
            //     'end_lon' => 'required|numeric'
            // ]);

            // $Movement = getAndCheckModelById(Movement::class, $id);

            // $Movement->update([
            //     'is_completed' => true,
            //     'end_latitude' => $request->input('end_lat'),
            //     'end_longitude' => $request->input('end_lon')
            // ]);

            // $movement_type = MovementType::findOrFail($Movement->movement_type_id);
            // if ($movement_type->is_onKM) {
            //     $totalPrice = $request->input('way') * $movement_type->price;
            // } else {
            //     $totalPrice = $movement_type->price;
            // }

            // $Calculation = Calculations::create([
            //     'driver_id' => Auth::id(),
            //     'taxi_movement_id' => $id,
            //     'totalPrice' => $totalPrice,
            //     'way' => $request->input('way')
            // ]);

            // getAndCheckModelById(User::class, Auth::id())->update([
            //     'driver_state' => 'ready'
            // ]);

            // $driverName = UserProfile::where('user_id', Auth::id())->first()->name;

            // $customerName = UserProfile::where('user_id',  $Movement->customer_id)->first()->name;

            // $from = $Movement->my_address;
            // $to = $Movement->destination_address;

            // MovementFindUnFindEvent::dispatch(
            //     $driverName,
            //     $customerName,
            //     'تم اكمال طلب الزبون من ' . $from . 'إلى ' . $to
            // );

            // return api_response(data: $Calculation->totalPrice, message: 'success');
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

            $request = Movement::select(
                'taxi_movements.id as request_id',
                'up.name',
                'up.phone_number',
                'taxi_movements.my_address as customer_address',
                'taxi_movements.destination_address as destination_address',
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
    public function destroy(Movement $Movement)
    {
        try {

            $Movement->delete();

            return redirect()->back()->with('success', 'تم حذف الطلب بنجاح');
        } catch (Exception $e) {
            return redirect()->back()->withErrors('هنالك خطأ في جلب البيانات الرجاء المحاولة مرة أخرى.\nالاخطاء:' . $e->getMessage())->withInput();
        }
    }
}

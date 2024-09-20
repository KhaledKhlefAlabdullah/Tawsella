<?php

namespace App\Http\Controllers;

use App\Enums\MovementRequestStatus;
use App\Enums\UserEnums\DriverState;
use App\Events\Movements\AcceptTransportationServiceRequestEvent;
use App\Events\Movements\CustomerCanceledMovementEvent;
use App\Events\Movements\DriverChangeMovementStateEvent;
use App\Events\Movements\RejectTransportationServiceRequestEvent;
use App\Events\Movements\RequestingTransportationServiceEvent;
use App\Http\Requests\TaxiMovements\MarkMovementAsCompletedRequest;
use App\Http\Requests\TaxiMovements\AcceptOrRejectMovementRequest;
use App\Http\Requests\TaxiMovements\FoundCustomerRequest;
use App\Http\Requests\TaxiMovements\TaxiMovementRequest;
use App\Models\Calculation;
use App\Models\TaxiMovement;
use App\Models\TaxiMovementType;
use App\Models\User;
use App\Models\UserProfile;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TaxiMovementController extends Controller
{

    /**
     * Show the form for creating a new resource.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function LifeTaxiMovements()
    {
        $currentDate = Carbon::now()->format('Y-m-d');

        $taxiMovement = TaxiMovement::where(['is_completed' => false, 'is_canceled' => false, 'request_state' => MovementRequestStatus::Accepted])
            ->whereDate('created_at', $currentDate)->get();

        return view('taxi_movement.LifeTaxiMovements', ['taxiMovement' => TaxiMovement::mappingMovements($taxiMovement)]);
    }


    /**
     * Get Completed taxi movements requests
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function completedTaxiMovements()
    {

        $completedRequests = TaxiMovement::where('is_completed', true)
            ->get();

        return view('taxi_movement.completedRequests', ['completedRequests' => TaxiMovement::mappingMovements($completedRequests)]);
    }

    /**
     * For View map for taxi location
     * @param TaxiMovement $taxiMovement
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function view_map(TaxiMovement $taxiMovement)
    {

        $data = [
            'driver_id' => $taxiMovement->driver_id,
            'lat' => $taxiMovement->end_latitude,
            'long' => $taxiMovement->end_longitude,
            'name' => $taxiMovement->driver->profile->name,
        ];

        return view('taxi_movement.map_completed', ['data' => $data])->with('success', __('success-view-map'));

    }

    /**
     * Create movements request
     * @param TaxiMovementRequest $request
     * @return JsonResponse all Movements and paths
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-46
     */
    public function store(TaxiMovementRequest $request)
    {
        try {
            TaxiMovement::calculateCanceledMovements(Auth::user());

            $validatedData = $request->validated();

            $driver = getAndCheckModelById(User::class, $validatedData['driver_id']);
            if ($driver->driver_stet != DriverState::Ready) {
                return api_response(
                    message: 'This driver is currently unavailable. Please try another driver.',
                    code: 409
                );
            }

            User::checkExistingCustomerMovements($validatedData['customer_id']);

            $taxiMovement = TaxiMovement::create($validatedData);

            event(new RequestingTransportationServiceEvent($taxiMovement));

            return api_response(message: 'Successfully creating movement');

        } catch (Exception $e) {
            return api_response(errors: [$e->getMessage()], message: 'حدث خطأ اثناء انشاء الطلب', code: 500);
        }
    }


    /**
     * Accept Taxi movement request
     * @param AcceptOrRejectMovementRequest $request
     * @param TaxiMovement $movement is the request who will be accepted
     * @return  \Illuminate\Http\RedirectResponse message and code
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-47
     */
    public function acceptRequest(AcceptOrRejectMovementRequest $request, TaxiMovement $taxiMovement)
    {
        try {
            DB::beginTransaction();
            $validatedData = $request->validated();
            $driver = getAndCheckModelById(User::class, $validatedData['driver_id']);
            $state = MovementRequestStatus::Accepted;

            User::processMovementState($taxiMovement, $state, null, $driver);

            // Update the driver state
            $driver->driver_state = DriverState::Reserved;
            $driver->save();
            $message = 'Request Accepted Successfully';
            DB::commit();
            AcceptTransportationServiceRequestEvent::dispatch($taxiMovement);

            return redirect()->back()->with('success', $message);
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (Exception $e) {
            return redirect()->back()->withErrors('هنالك خطأ في جلب البيانات الرجاء المحاولة مرة أخرى.' . "\n errors:" . $e->getMessage())->withInput();
        }
    }

    /**
     * Reject Taxi movement request
     * @param AcceptOrRejectMovementRequest $request contains the request details
     * @param TaxiMovement $taxiMovement is the request who will be rejected
     * @return \Illuminate\Http\RedirectResponse status message and code
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-48
     */
    public function rejectMovement(AcceptOrRejectMovementRequest $request, TaxiMovement $taxiMovement)
    {
        try {
            DB::beginTransaction();
            $validatedData = $request->validated();

            $state = MovementRequestStatus::Rejected;

            User::processMovementState($taxiMovement, $state, $validatedData['message']);

            DB::commit();
            RejectTransportationServiceRequestEvent::dispatch($taxiMovement);

            return redirect()->back()->with('success', __('success-reject-movement'));
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (Exception $e) {
            return redirect()->back()->withErrors('هنالك خطأ في جلب البيانات الرجاء المحاولة مرة أخرى.' . "\n errors:" . $e->getMessage())->withInput();
        }
    }

    /**
     * Send notification to the dashboard if the driver find or don't find the customer
     * @param FoundCustomerRequest $request
     * @param TaxiMovement $taxiMovement
     * @return JsonResponse
     */
    public function foundCustomer(FoundCustomerRequest $request, TaxiMovement $taxiMovement)
    {
        try {
            $validatedData = $request->validated();

            DriverChangeMovementStateEvent::dispatch(
                $taxiMovement, $validatedData['state']
            );

            if (!$validatedData['state']) {
                $taxiMovement->delete();
            }

            return api_response(message: 'Successfully found customer');
        } catch (Exception $e) {
            return api_response(errors: $e->getMessage(), message: 'حدث خطأ في ايجاد او عدم ايجاد الزبون', code: 500);
        }
    }

    /**
     * Make the movement is completed
     * @param MarkMovementAsCompletedRequest $request contains the end point location
     * @param TaxiMovement $movement is the movement who will be ended
     * @return JsonResponse status message and code
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-49
     */
    public function makeMovementIsCompleted(MarkMovementAsCompletedRequest $request, TaxiMovement $taxiMovement)
    {
        try {
            DB::beginTransaction();
            $validatedData = $request->validated();

            $taxiMovement->update([
                'is_completed' => true,
                'end_latitude' => $validatedData['end_lat'],
                'end_longitude' => $validatedData['end_lon']
            ]);

            $calculation = TaxiMovement::calculateAmountPaid($taxiMovement, $validatedData['way']);

            $taxiMovement->driver()->update([
                'driver_state' => DriverState::Ready
            ]);

            DB::commit();
            DriverChangeMovementStateEvent::dispatch(
                $taxiMovement,
                'completed-movement'
            );

            return api_response(data: $calculation->totalPrice, message: 'Successfully completed movement request');
        } catch (Exception $e) {
            DB::rollBack();
            return api_response(errors: $e->getMessage(), message: 'error', code: 500);
        }
    }

    /**
     * Canceled movement by customer
     * @param TaxiMovement $taxiMovement is the movement who will be ended
     * @return JsonResponse status message and code
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-49
     */
    public function canceledMovement(TaxiMovement $taxiMovement)
    {
        try {

            $taxiMovement->update([
                'is_canceled' => true
            ]);

            TaxiMovement::calculateCanceledMovements(Auth::user());

            CustomerCanceledMovementEvent::dispatch($taxiMovement);

            return api_response(message: 'Movement Canceled Successfully');
        } catch (Exception $e) {
            return api_response(errors: [$e->getMessage()], message: 'Movement Canceled error', code: 500);
        }
    }

    //todo need to remove and use realtime
    /**
     * Send Taxi movemnt request details
     */
    public function get_request_data(string $driver_id)
    {
        try {

            $request = TaxiMovement::select(
                'taxi_movements.id as request_id',
                'up.name',
                'up.phone_number',
                'taxi_movements.start_address as customer_address',
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
                ->where(['taxi_movements.driver_id' => $driver_id, 'is_completed' => false, 'is_canceled' => false, 'is_redirected' => true])
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
     * @param TaxiMovement $taxiMovement
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(TaxiMovement $taxiMovement)
    {
        try {

            $taxiMovement->delete();

            return redirect()->back()->with('success', 'تم حذف الطلب بنجاح');
        } catch (Exception $e) {
            return redirect()->back()->withErrors('هنالك خطأ في جلب البيانات الرجاء المحاولة مرة أخرى.' . "\n errors:" . $e->getMessage())->withInput();
        }
    }
}

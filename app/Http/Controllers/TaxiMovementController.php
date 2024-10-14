<?php

namespace App\Http\Controllers;

use App\Enums\MovementRequestStatus;
use App\Enums\UserEnums\DriverState;
use App\Enums\UserEnums\UserGender;
use App\Events\Movements\AcceptTransportationServiceRequestEvent;
use App\Events\Movements\CustomerCanceledMovementEvent;
use App\Events\Movements\DriverChangeMovementStateEvent;
use App\Events\Movements\RejectTransportationServiceRequestEvent;
use App\Events\Movements\RequestingTransportationServiceEvent;
use App\Http\Requests\TaxiMovements\MarkMovementAsCompletedRequest;
use App\Http\Requests\TaxiMovements\AcceptOrRejectMovementRequest;
use App\Http\Requests\TaxiMovements\FoundCustomerRequest;
use App\Http\Requests\TaxiMovements\TaxiMovementRequest;
use App\Models\Chat;
use App\Models\ChatMember;
use App\Models\TaxiMovement;
use App\Models\User;
use App\Services\PaginationService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TaxiMovementController extends Controller
{

    protected $paginationService;

    public function __construct(PaginationService $paginationService)
    {
        $this->paginationService = $paginationService;
    }

    /**
     * Show the form for creating a new resource.
     * @return JsonResponse
     * @author Khaled <khaledabdullah2001104@gmail.com>
     */
    public function LifeTaxiMovements(Request $request)
    {
        $currentDate = Carbon::now()->format('Y-m-d');
        $query = TaxiMovement::query()->where(['is_completed' => false, 'is_canceled' => false, 'request_state' => MovementRequestStatus::Accepted])
            ->whereDate('created_at', $currentDate);

        $taxiMovement = $this->paginationService->applyPagination($query, $request);

        return api_response(data: TaxiMovement::mappingMovements($taxiMovement->items()), message: 'Successfully getting life taxiMovements.', pagination: get_pagination($taxiMovement, $request));
    }

    /**
     * Get Completed taxi movements requests
     * @return JsonResponse
     * @author Khaled <khaledabdullah2001104@gmail.com>
     */
    public function completedTaxiMovements(Request $request)
    {
        $query = TaxiMovement::query()->where(['is_completed' => true, 'is_canceled' => false]);

        $completedRequests = $this->paginationService->applyPagination($query, $request);

        return api_response(data: TaxiMovement::mappingMovements($completedRequests->items()), message: 'Successfully getting completed taxiMovements.', pagination: get_pagination($completedRequests, $request));
    }

    /**
     * For View map for taxi location
     * @param TaxiMovement $taxiMovement
     * @return JsonResponse
     * @author Khaled <khaledabdullah2001104@gmail.com>
     */
    public function view_map(TaxiMovement $taxiMovement)
    {
        $data = [
            'driver_id' => $taxiMovement->driver_id,
            'lat' => $taxiMovement->end_latitude,
            'long' => $taxiMovement->end_longitude,
            'name' => $taxiMovement->driver()->profile?->name,
            'path' => json_decode($taxiMovement->path) ?? []
        ];
        return api_response(data: $data, message: 'Successfully getting map taxiMovement.');
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
            $canceledMovementResponse = TaxiMovement::calculateCanceledMovements(Auth::user());
            if ($canceledMovementResponse) {
                return $canceledMovementResponse;
            }
            $validatedData = $request->validated();
            $ExistsCustomerMovementsResponse = User::checkExistingCustomerMovements($validatedData['customer_id']);
            if ($ExistsCustomerMovementsResponse) {
                return $ExistsCustomerMovementsResponse;
            }
            $validatedData['gender'] = UserGender::getValue($validatedData['gender']);
            $taxiMovement = TaxiMovement::create($validatedData);
            broadcast(new RequestingTransportationServiceEvent($taxiMovement));
            return api_response(data: ['movement_id' => $taxiMovement->id], message: 'Successfully creating movement');
        } catch (Exception $e) {
            return api_response(errors: [$e->getMessage()], message: 'Error in creatting taxi movement', code: 500);
        }
    }


    /**
     * Accept Taxi movement request
     * @param AcceptOrRejectMovementRequest $request
     * @param TaxiMovement $movement is the request who will be accepted
     * @return  JsonResponse message and code
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-47
     */
    public function acceptRequest(AcceptOrRejectMovementRequest $request, TaxiMovement $taxiMovement)
    {
        try {
            DB::beginTransaction();
            $validatedData = $request->validated();
            $driver = User::find($validatedData['driver_id']);
            if ($driver->driver_state != DriverState::Ready) {
                return api_response(message: 'This driver is currently unavailable. Please try another driver.', code: 409);
            }
            $state = MovementRequestStatus::Accepted;
            $message = __('accepted-movement-success');
            $processMovementStateRequest = User::processMovementState($taxiMovement, $state, $message, $driver);
            if ($processMovementStateRequest) {
                return $processMovementStateRequest;
            }
            // Update the driver state
            $driver->driver_state = DriverState::Reserved;
            $driver->save();
            $customer = User::find($taxiMovement->customer->id);
            $createChatBetweenUsersRequest = Chat::CreateChatBetweenUserAndDriver($customer, $driver);
            if ($createChatBetweenUsersRequest) {
                return $createChatBetweenUsersRequest;
            }
            DB::commit();

            AcceptTransportationServiceRequestEvent::dispatch($taxiMovement);

            return api_response(message: $message);
        } catch (Exception $e) {
            return api_response(errors: [$e->getMessage()], message: 'Error in accept movement', code: 500);
        }
    }

    /**
     * Reject Taxi movement request
     * @param AcceptOrRejectMovementRequest $request contains the request details
     * @param TaxiMovement $taxiMovement is the request who will be rejected
     * @return JsonResponse status message and code
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-48
     */
    public function rejectMovement(AcceptOrRejectMovementRequest $request, TaxiMovement $taxiMovement)
    {
        try {
            DB::beginTransaction();
            $validatedData = $request->validated();

            $state = MovementRequestStatus::Rejected;

            $processMovementStateRequest = User::processMovementState($taxiMovement, $state, $validatedData['message']);
            if ($processMovementStateRequest) {
                return $processMovementStateRequest;
            }
            DB::commit();
            RejectTransportationServiceRequestEvent::dispatch($taxiMovement);

            return api_response(message: 'Successfully rejecting movement');
        } catch (Exception $e) {
            return api_response(errors: [$e->getMessage()], message: 'Error in rejecet movement', code: 500);
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

            if ($validatedData['state']) {
                $taxiMovement->state_message = __('customer-was-found');

            } else {
                $taxiMovement->state_message = __('customer-was-not-found');
            }

            return api_response(message: 'Successfully found customer');
        } catch (Exception $e) {
            return api_response(errors: [$e->getMessage()], message: 'Error in find customer', code: 500);
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
                'end_latitude' => $validatedData['end_latitude'],
                'end_longitude' => $validatedData['end_longitude']
            ]);

            $taxiMovement->incrementMovementCount($taxiMovement->customer_id);

            $calculation = TaxiMovement::calculateAmountPaid($taxiMovement, $validatedData['distance']);

            $taxiMovement->driver()->update([
                'driver_state' => DriverState::Ready
            ]);

            DB::commit();
            DriverChangeMovementStateEvent::dispatch(
                $taxiMovement,
                'completed-movement'
            );

            return api_response(data: ['amount' => $calculation->totalPrice], message: 'Successfully completed movement request');
        } catch (Exception $e) {
            DB::rollBack();
            return api_response(errors: [$e->getMessage()], message: 'Error in make movement completed', code: 500);
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

            CustomerCanceledMovementEvent::dispatch($taxiMovement);

            $calculateCanceledMovementsRequest = TaxiMovement::calculateCanceledMovements(Auth::user());
            if ($calculateCanceledMovementsRequest) {
                return $calculateCanceledMovementsRequest;
            }
            return api_response(message: 'Movement Canceled Successfully');
        } catch (Exception $e) {
            return api_response(errors: [$e->getMessage()], message: 'Movement Canceled error', code: 500);
        }
    }

    /**
     * @return array|JsonResponse
     */
    public function getLastRequestForCustomer()
    {

        $lastRequest = Auth::user()->customer_movements()
            ->where('is_completed', false)
            ->where('is_canceled', false)
            ->where('is_redirected', true)
            ->whereDate('created_at', Carbon::today())
            ->latest()
            ->first();

        if (!$lastRequest) {
            return api_response(message: 'There no request for this customer', code: 404);
        }

        $driver_profile = $lastRequest->driver ? $lastRequest->driver->profile : null;
        $chat = ChatMember::where('member_id', Auth::id())
            ->whereIn('chat_id', function ($query) use ($driver_profile) {
                $query->select('chat_id')
                    ->from('chat_members')
                    ->where('member_id', $driver_profile->user_id)
                    ->get(); // Ensure both members are present
            })
            ->first();

        return [
            'chat_id' => $chat->chat_id ?? '',
            'request_id' => $lastRequest->id,
            'message' => $lastRequest->state_message,
            'driver' => $driver_profile
        ];
    }

    /**
     * Send Taxi movemnt request details
     */
    public function get_request_data(string $driver_id)
    {
        $lastRequest = Auth::user()->driver_movements()
            ->where('is_completed', false)
            ->where('is_canceled', false)
            ->where('is_redirected', true)
            ->whereDate('created_at', Carbon::today())
            ->latest()
            ->first();

        if (!$lastRequest) {
            return api_response(message: 'There no request for this driver', code: 404);
        }

        $customer_profile = $lastRequest->customer ? $lastRequest->customer->profile : null;
        $chat = ChatMember::where('member_id', Auth::id())
            ->whereIn('chat_id', function ($query) use ($customer_profile) {
                $query->select('chat_id')
                    ->from('chat_members')
                    ->where('member_id', $customer_profile->user_id)
                    ->get();
            })
            ->first();

        return [
            'chat_id' => $chat->chat_id ?? '',
            'request_id' => $lastRequest->id,
            'customer_id' => $lastRequest->customer->id,
            'name' => $lastRequest->customer?->profile->name,
            'phone_number' => $lastRequest->customer?->profile->phone_number,
            'customer_address' => $lastRequest->start_address,
            'destination_address' => $lastRequest->destination_address,
            'gender' => UserGender::getKey($lastRequest->gender),
            'location_lat' => $lastRequest->start_latitude,
            'location_long' => $lastRequest->start_longitude,
            'type' => $lastRequest->movement_type->type,
            'price' => $lastRequest->movement_type->price,
            'is_onKM' => $lastRequest->movement_type->is_onKM
        ];
//        try {
//            //todo need to remove and use realtime
//            $request = TaxiMovement::select(
//                'taxi_movements.id as request_id',
//                'up.name',
//                'up.phone_number',
//                'taxi_movements.start_address as customer_address',
//                'taxi_movements.destination_address as destination_address',
//                'taxi_movements.gender as gender',
//                'taxi_movements.start_latitude as location_lat',
//                'taxi_movements.start_longitude as location_long',
//                'tmt.type',
//                'tmt.price',
//                'tmt.is_onKM'
//            )
//                ->join('user_profiles as up', 'taxi_movements.customer_id', '=', 'up.user_id')
//                ->join('taxi_movement_types as tmt', 'taxi_movements.movement_type_id', '=', 'tmt.id')
//                ->where(['taxi_movements.driver_id' => $driver_id, 'is_completed' => false, 'is_canceled' => false, 'is_redirected' => true])
//                ->whereDate('taxi_movements.created_at', today())
//                ->first();
//            if ($request)
//                return api_response(data: $request, message: 'نجح في الحول على بيانات');
//            return api_response(message: 'there now data');
//        } catch (Exception $e) {
//            return api_response(errors: [$e->getMessage()], message: 'حدث خطأ في الحصول على بيانات', code: 500);
//        }
    }

    /**
     * Remove the specified resource from storage.
     * @param TaxiMovement $taxiMovement
     * @return JsonResponse
     */
    public function destroy(TaxiMovement $taxiMovement)
    {
        try {
            $taxiMovement->delete();
            return api_response(message: 'Successfully deleted taxi movement');
        } catch (Exception $e) {
            return api_response(errors: [$e->getMessage()], message: 'Error in deleted taxi movement', code: 500);
        }
    }
}

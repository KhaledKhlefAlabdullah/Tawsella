<?php

namespace App\Http\Controllers;

use App\Enums\MovementRequestStatus;
use App\Enums\UserEnums\DriverState;
use App\Enums\UserEnums\UserGender;
use App\Events\Movements\CustomerCanceledMovementEvent;
use App\Events\Movements\CustomerFoundEvent;
use App\Events\Movements\MovementCompleted;
use App\Events\Movements\RequestingTransportationServiceEvent;
use App\Http\Requests\TaxiMovements\MarkMovementAsCompletedRequest;
use App\Http\Requests\TaxiMovements\AcceptOrRejectMovementRequest;
use App\Http\Requests\TaxiMovements\FoundCustomerRequest;
use App\Http\Requests\TaxiMovements\TaxiMovementRequest;
use App\Models\Chat;
use App\Models\ChatMember;
use App\Models\TaxiMovement;
use App\Models\User;
use App\Services\FcmNotificationService;
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
    protected $fcmNotificationService;

    public function __construct(PaginationService $paginationService, FcmNotificationService $fcmNotificationService)
    {
        $this->paginationService = $paginationService;
        $this->fcmNotificationService = $fcmNotificationService;
    }

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $currentDate = Carbon::now()->format('Y-m-d');
        $movements = TaxiMovement::query()
            ->where([
                'is_completed' => false,
                'is_canceled' => false,
                'request_state' => MovementRequestStatus::Pending
            ])
            ->whereDate('created_at', $currentDate)
            ->orderBy('created_at', 'desc')
            ->get();

        return api_response(data: TaxiMovement::mappingMovements($movements), message: 'تم جلب بيانات الرحلات بنجاح');
    }

    /**
     * Show the form for creating a new resource.
     * @return JsonResponse
     * @author Khaled <khaledabdullah2001104@gmail.com>
     */
    public function LifeTaxiMovements(Request $request): JsonResponse
    {
        $currentDate = Carbon::now()->format('Y-m-d');
        $query = TaxiMovement::query()->where(['is_completed' => false, 'is_canceled' => false, 'request_state' => MovementRequestStatus::Accepted])
            ->whereDate('created_at', $currentDate);

        $taxiMovement = $this->paginationService->applyPagination($query, $request);

        return api_response(data: TaxiMovement::mappingMovements($taxiMovement->items()), message: 'تم جلب بيانات الرحلات الحية بنجاح', pagination: get_pagination($taxiMovement, $request));
    }

    /**
     * Get Completed taxi movements requests
     * @return JsonResponse
     * @author Khaled <khaledabdullah2001104@gmail.com>
     */
    public function completedTaxiMovements(Request $request): JsonResponse
    {
        $query = TaxiMovement::query()->where(['is_completed' => true, 'is_canceled' => false]);

        $completedRequests = $this->paginationService->applyPagination($query, $request);

        return api_response(data: TaxiMovement::mappingMovements($completedRequests->items()), message: 'تم جلب بيانات الرحلات المكتملة بنجاح', pagination: get_pagination($completedRequests, $request));
    }

    /**
     * Get Canceled taxi movements requests
     * @return JsonResponse
     * @author Khaled <khaledabdullah2001104@gmail.com>
     */
    public function canceledTaxiMovements(Request $request): JsonResponse
    {
        $query = TaxiMovement::query()->where(['is_completed' => false, 'is_canceled' => true]);

        $completedRequests = $this->paginationService->applyPagination($query, $request);

        return api_response(data: TaxiMovement::mappingMovements($completedRequests->items()), message: 'تم جلب بيانات الرحلات الملغية بنجاح', pagination: get_pagination($completedRequests, $request));
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
            'name' => $taxiMovement->driver?->profile?->name,
            'path' => json_decode($taxiMovement->path) ?? []
        ];
        return api_response(data: $data, message: 'تم جلب بيانات الخريطة للطلب بنجاح');
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
            event(new RequestingTransportationServiceEvent($taxiMovement));
            $admin = User::find(getAdminId());
            $userName = Auth::check() ? Auth::user()->profile->name : 'Guest';

            send_notifications($admin, [
                'title' => 'new movement request',
                'body' => [
                    'message' => "The user {$userName} requested a new movement request."
                ],
            ]);
            return api_response(data: ['movement_id' => $taxiMovement->id], message: 'تم انشاء طلب بنجاح');
        } catch (Exception $e) {
            return api_response(message: 'هناك خطأ في إنشاء طلب', code: 500, errors: [$e->getMessage()]);
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
                return api_response(message: 'هذا السائق غير متاح حاليا، حاول مع سائق أخر من فضلك', code: 409);
            }
            $state = MovementRequestStatus::Accepted;
            $message = 'تم قبول الطلب بنجاح';
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

            $customerPayload = [
                'notification' => [
                    'title' => 'تم قبول طلبك!',
                    'body' => 'الطلب الذي أرسلته منذ قليل تم قبوله بنجاح.'
                ],
                'data' => [
                    'request_id' => (string)$taxiMovement->id,
                    'customer' => json_encode($taxiMovement->customer->profile, JSON_THROW_ON_ERROR),
                    'message' => 'الطلب الذي أرسلته منذ قليل تم قبوله بنجاح.',
                    'taxiMovementInfo' => json_encode($this->getDriverData($taxiMovement), JSON_THROW_ON_ERROR)
                ],
            ];


            $customerRecipientValue = $customer->device_token;
            send_notifications($customer, $customerPayload['notification']);
            if (!is_null($customerRecipientValue)) {
                $this->fcmNotificationService->sendNotification($customerPayload, $customerRecipientValue);
            }
            $driverPayload = [
                'notification' => [
                    'title' => 'لديك طلب جديد!',
                    'body' => 'تم إرسال طلب جديد، يرجى مراجعته واتخاذ الإجراء المناسب.',
                ],
                'data' => [
                    'request_id' => (string)$taxiMovement->id,
                    'customer' => json_encode($customer->profile, JSON_THROW_ON_ERROR),
                    'message' => 'تم إرسال طلب جديد، يرجى مراجعته واتخاذ الإجراء المناسب.',
                    'taxiMovementInfo' => json_encode($this->getDriverData($taxiMovement), JSON_THROW_ON_ERROR)
                ],
            ];

            $driverRecipientValue = $driver->device_token;
            send_notifications($driver, $driverPayload['notification']);
            if (!is_null($driverRecipientValue))
                $this->fcmNotificationService->sendNotification($driverPayload, $driverRecipientValue);

            return api_response(message: $message);
        } catch (Exception $e) {
            return api_response(message: 'هناك خطأ في قبول الطلب', code: 500, errors: [$e->getMessage()]);
        }
    }

    /**
     * Get the driver-related data.
     *
     * @return array
     */
    public function getDriverData(TaxiMovement $taxiMovement): array
    {
        return [
            'gender' => UserGender::getKey($taxiMovement->gender),
            'customer_address' => $taxiMovement->start_address,
            'destination_address' => $taxiMovement->destination_address,
            'location_lat' => $taxiMovement->start_latitude,
            'location_long' => $taxiMovement->start_longitude,
            'type' => $taxiMovement->movement_type->type,
        ];
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
            //RejectTransportationServiceRequestEvent::dispatch($taxiMovement);
            $customerPayload = [
                'notification' => [
                    'title' => 'تم رفض طلبك!',
                    'body' => array_key_exists('message', $validatedData) ? $validatedData['message'] : 'نرجو المعذرة، تم رفض طلبك للأسف.',
                ]
            ];
            $customer = $taxiMovement->customer;
            $customerRecipientValue = $customer->device_token;
            send_notifications($customer, $customerPayload['notification']);
            if (!is_null($customerRecipientValue))
                $this->fcmNotificationService->sendNotification($customerPayload, $customerRecipientValue);

            return api_response(message: 'تم رفض الطلب بنجاح');
        } catch (Exception $e) {
            return api_response(message: 'هناك خطأ في رفض الطلب', code: 500, errors: [$e->getMessage()]);
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
            $driverName = $taxiMovement->driver->profile->name;
            $customerName = $taxiMovement->customer->profile->name;
            $message = 'السائق: ' . $driverName . ($validatedData['state'] ? ' وجد' : ' لم يجد') . ' الزبون: ' . $customerName;
            CustomerFoundEvent::dispatch($driverName, $customerName, $message);
            $admin = User::find(getAdminId());
            send_notifications($admin, [
                'title' => 'تم إيجاد الزبون!',
                'body' => [
                    'request_id' => $taxiMovement->id,
                    'customer' => $taxiMovement->customer->profile,
                    'message' => $message
                ]
            ]);

            if ($validatedData['state']) {
                $taxiMovement->state_message = __('customer-was-found');

            } else {
                $taxiMovement->state_message = __('customer-was-not-found');
            }

            return api_response(message: 'تم ايجاد الزبون بنجاح');
        } catch (Exception $e) {
            return api_response(message: 'حصل خطأ أثناء تعليم الزبون كموجود', code: 500, errors: [$e->getMessage()]);
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
                'end_longitude' => $validatedData['end_longitude'],
                'notes' => array_key_exists('notes', $validatedData) ? $validatedData['notes'] : null,
            ]);

            $taxiMovement->incrementMovementCount($taxiMovement->customer_id);

            $calculation = TaxiMovement::calculateAmountPaid($taxiMovement, $validatedData);

            $driver = User::find($taxiMovement->driver_id);
            $driver->update([
                'driver_state' => DriverState::Ready
            ]);

            DB::commit();

            $driver = $taxiMovement->driver;
            $customer = $taxiMovement->customer;
            $from = $taxiMovement->start_address;
            $to = $taxiMovement->destination_address;
            $message = 'تم اكمال طلب من: ' . $from . ' إلى: ' . $to;
            MovementCompleted::dispatch($driver, $customer, $message);
            $admin = User::find(getAdminId());
            send_notifications($admin, [
                'title' => 'الطلب مكتمل!',
                'body' => [
                    'request_id' => $taxiMovement->id,
                    'customer' => $taxiMovement->customer->profile,
                    'message' => $message,
                    'taxiMovementInfo' => $this->getDriverData($taxiMovement)
                ]
            ]);
            return api_response(data: [
                'amount' => $calculation->totalPrice,
                'distance' => $calculation->distance,
                'additional_amount' => $calculation->additional_amount,
                'reason' => $calculation->reason,
            ], message: 'Successfully completed movement request');

        } catch (Exception $e) {
            DB::rollBack();
            return api_response(message: 'Error in make movement completed', code: 500, errors: [$e->getMessage()]);
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
            $profile = optional($taxiMovement->customer)->profile ? $taxiMovement->customer->profile->toArray() : 'Guest';

            $adminId = getAdminId();
            if ($adminId) {
                $admin = User::find($adminId);
                if ($admin) {
                    send_notifications($admin->id, [
                        'title' => 'Movement canceled!',
                        'body' => [
                            'request_id' => $taxiMovement->id,
                            'customer' => $profile,
                            'message' => 'The customer canceled the movement',
                            'taxiMovementInfo' => $this->getDriverData($taxiMovement)
                        ]
                    ]);
                }
            }

            if ($taxiMovement->is_redirected) {
                $driver = $taxiMovement->driver;
                if ($driver) {
                    $driver->update([
                        'driver_state' => DriverState::Ready
                    ]);

                    $driverPayload = [
                        'notification' => [
                            'title' => 'Movement canceled!',
                            'body' => 'The customer canceled the movement.',
                        ],
                        'data' => [
                            'request_id' => (string)$taxiMovement->id,
                            'customer' => optional($taxiMovement->customer->profile)->toArray() ?? 'Guest',
                            'message' => 'The customer canceled the movement',
                            'taxiMovementInfo' => $this->getDriverData($taxiMovement),
                        ],
                    ];

                    $driveResentValue = $driver->device_token;
                    send_notifications($driver->id, $driverPayload['notification']);
                    $this->fcmNotificationService->sendNotification($driverPayload, $driveResentValue);
                }
            }

            $calculateCanceledMovementsRequest = TaxiMovement::calculateCanceledMovements(Auth::user());
            if ($calculateCanceledMovementsRequest) {
                return $calculateCanceledMovementsRequest;
            }
            return api_response(message: 'Movement Canceled Successfully');

        } catch (Exception $e) {
            return api_response(message: 'Movement Canceled error', code: 500, errors: [$e->getMessage()]);
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
        if (!is_null($driver_profile)) {
            $chat = ChatMember::where('member_id', Auth::id())
                ->whereIn('chat_id', function ($query) use ($driver_profile) {
                    $query->select('chat_id')
                        ->from('chat_members')
                        ->where('member_id', $driver_profile->user_id)
                        ->get(); // Ensure both members are present
                })
                ->first();
        }
        return [
            'chat_id' => $chat->chat_id ?? '',
            'request_id' => $lastRequest->id,
            'message' => $lastRequest->state_message,
            'driver' => $driver_profile
        ];
    }

    /**
     * Send Taxi movement request details
     * @param string $driver_id
     * @return mixed
     * @throws \BenSampo\Enum\Exceptions\InvalidEnumMemberException
     */
    public function get_request_data(string $driver_id)
    {
        $driver = User::find($driver_id);
        $lastRequest = $driver->driver_movements()
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
            'is_onKM' => (boolean)$lastRequest->movement_type->is_onKM
        ];
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
            return api_response(message: 'Error in deleted taxi movement', code: 500, errors: [$e->getMessage()]);
        }
    }
}

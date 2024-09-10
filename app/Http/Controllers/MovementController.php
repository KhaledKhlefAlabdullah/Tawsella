<?php

namespace App\Http\Controllers;

use App\Enums\MovementRequestStatus;
use App\Enums\UserEnums\DriverState;
use App\Enums\UserEnums\UserType;
use App\Events\Movements\AcceptTransportationServiceRequestEvent;
use App\Events\Movements\DriverChangeStateEvent;
use App\Events\Movements\RejecttTransportationServiceRequestEvent;
use App\Events\Movements\RequestingTransportationServiceEvent;
use App\Http\Requests\Movements\AcceptOrRejectMovementRequest;
use App\Http\Requests\Movements\MarkMovementAsCompletedRequest;
use App\Http\Requests\Movements\MovementRequest;
use App\Http\Requests\NearestDriverRequest;
use App\Models\Movement;
use App\Models\User;
use App\Models\UserProfile;
use BaconQrCode\Renderer\Path\Move;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
            ->whereNotIn('user_type', [UserType::Admin(), UserType::Customer()])
            ->where(['user_type' => $validatedData['movement_type'], 'driver_state' => DriverState::Ready()])
            ->where('is_active', true)
            ->get();

        if (empty($drivers)) {
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

            $driver = getAndCheckModelById(User::class, $validatedData['driver_id']);
            if ($driver->driver_stet != DriverState::Ready) {
                return api_response(
                    message: 'This driver is currently unavailable. Please try another driver.',
                    code: 409
                );
            }

            User::checkExistingCustomerMovements($validatedData['customer_id']);

            $movement = Movement::create($validatedData);

            event(new RequestingTransportationServiceEvent($movement));

            return api_response(message: 'Successfully creating movement');
        } catch (Exception $e) {
            return api_response(errors: [$e->getMessage()], message: 'Creating movements error', code: 500);
        }
    }


    /**
     * Accept Taxi movement request
     * @param Movement $movement is the request who will be accepted
     * @return JsonResponse status message and code
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-47
     */
    public function acceptMovement(Movement $movement)
    {
        try {
            DB::beginTransaction();
            $state = MovementRequestStatus::Accepted;

            User::processMovementState($movement, $state);

            $driver_id = Auth::id();
            $driver = getAndCheckModelById(User::class, $driver_id);

            // Update the driver state
            $driver->driver_state = DriverState::Reserved;
            $driver->save();

            AcceptTransportationServiceRequestEvent::dispatch($movement);
            $message = 'Request Accepted Successfully';
            DB::commit();
            return api_response(message: $message);
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return api_response(
                errors: [$e->getMessage()],
                message: 'Driver not found.',
                code: 404
            );
        } catch (Exception $e) {
            DB::rollBack();
            return api_response(
                errors: [$e->getMessage()],
                message: 'An error occurred while accepting the request.',
                code: 500
            );
        }
    }

    /**
     * Reject Taxi movement request
     * @param AcceptOrRejectMovementRequest $request contains the request details
     * @param Movement $movement is the request who will be rejected
     * @return JsonResponse status message and code
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-48
     */
    public function rejectMovement(AcceptOrRejectMovementRequest $request, Movement $movement)
    {
        try {
            $validatedData = $request->validated();

            $state = MovementRequestStatus::Rejected;

            User::processMovementState($movement, $state, $validatedData['message']);

            RejecttTransportationServiceRequestEvent::dispatch($movement);

            $message = 'Request Rejected Successfully';

            return api_response(message: $message);

        } catch (Exception $e) {
            return api_response(
                errors: [$e->getMessage()],
                message: 'An error occurred while rejecting the request.',
                code: 500
            );
        }
    }

    /**
     * Make the movement is completed
     * @param MarkMovementAsCompletedRequest $request contains the end point location
     * @param Movement $movement is the movement who will be ended
     * @return JsonResponse status message and code
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-48
     */
    public function markMovementIsCompleted(MarkMovementAsCompletedRequest $request, Movement $movement)
    {
        try {
            DB::beginTransaction();
            $validatedData = $request->validated();
            $driver = Auth::user();

            $amountPaid = Movement::calculateAmountPaid($movement, $driver);

            $movement->update([
                'is_completed' => true,
                'distance' => $validatedData['distance'],
                'end_latitude' => $validatedData['end_latitude'],
                'end_longitude' => $validatedData['end_longitude'],
                'amountPaid' => $amountPaid
            ]);

            $driver->driver_state = DriverState::Ready;
            $driver->save();

            $data = [
                'distance' => $movement->distance,
                'start_latitude' => $movement->start_latitude,
                'start_longitude' => $movement->start_longitude,
                'end_latitude' => $movement->end_latitude,
                'end_longitude' => $movement->end_longitude,
                'amountPaid' => $movement->amountPaid
            ];
            DB::commit();
            DriverChangeStateEvent::dispatch($driver);
            return api_response(data: $data, message: 'success');
        } catch (Exception $e) {
            DB::rollBack();
            return api_response(errors: $e->getMessage(), message: 'error', code: 500);
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

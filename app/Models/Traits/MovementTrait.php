<?php

namespace App\Models\Traits;

use App\Models\TaxiMovement;
use App\Models\User;
use Carbon\Carbon;

trait MovementTrait
{
    /**
     * Mapping movements for view
     * @param $movements
     * @return mixed
     */
    public static function mappingMovements($movements)
    {
        $mappedMovements = $movements->map(function ($movement) {
            $movement_state = function ($movement) {
                $state = '';
                if ($movement->request_state == 'accepted' and !$movement->is_completed) {
                    $state = 'live';
                }

                if ($movement->request_state == 'rejected') {
                    $state = 'rejected by driver';
                }
                if ($movement->is_canceled) {
                    $state = 'canceled by customer';
                }
                if ($movement->request_state == 'pending') {
                    $state = 'pending by driver';
                }
                return $state;
            };

            return [
                'movement_id' => $movement->id,
                'start_latitude' => $movement->start_latitude,
                'start_longitude' => $movement->start_longitude,
                'end_latitude' => $movement->end_latitude,
                'end_longitude' => $movement->end_longitude,
                'path' => json_decode($movement->path),
                'movement_state' => $movement_state($movement),
                'state_message' => $movement->state_message,
                'distance' => $movement->distance,
                'the_amount_paid' => $movement->amountPaid,
                'driver' => [
                    'id' => $movement->driver->id,
                    'name' => $movement->driver->profile->name,
                    'avatar' => $movement->driver->profile->avatar,
                ],
                'customer' => [
                    'id' => $movement->customer->id,
                    'name' => $movement->customer->profile->name,
                    'avatar' => $movement->customer->profile->avatar,
                ],
            ];
        });

        return $mappedMovements;
    }

    /**
     * Calculate amount paid for movements
     * @param TaxiMovement $movement
     * @param User $driver
     * @return float|int|mixed
     */
    public static function calculateAmountPaid(TaxiMovement $movement, User $driver)
    {
        $amountPaid = 0;
        if ($movement->is_onKM) {
            $amountPaid = $movement->distance * $driver->KMPaid;
        } else {
            $amountPaid = $driver->movementPaid;
        }

        return $amountPaid;
    }

    /**
     * Calculate canceled movements for user
     * @param User $user
     * @return \Illuminate\Http\JsonResponse|void
     * @throws \Exception
     */
    public static function calculateCanceledMovements(User $user)
    {
        // Get today's date without time
        $today = Carbon::today();

        // Filter movements for those that are canceled and created today
        $todayCanceledMovements = $user->movements->filter(function ($movement) use ($today) {
            return $movement->is_canceled && $movement->created_at->isSameDay($today);
        })->count();

        if ($todayCanceledMovements >= 10) {
            $message = 'You have exceeded the allowed number of canceled movements for today.';
            send_notifications($user, $message, ['database','mail']);
            return api_response(message: $message, code: 429);
        }

        // Get the date ten days ago from today
        $tenDaysAgo = $today->copy()->subDays(10);

        // Filter movements for those that are canceled and created within the last ten days
        $lastTenDaysCanceledMovements = $user->movements->filter(function ($movement) use ($tenDaysAgo, $today) {
            return $movement->is_canceled && $movement->created_at->between($tenDaysAgo, $today);
        })->count();

        if ($lastTenDaysCanceledMovements >= 30) {
            $user->is_active = false;
            $user->save();
            return api_response(message: 'Account deactivated due to excessive cancellations in the last 10 days.', code: 423);
        }
    }

    /**
     * Get today Movement requests
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
     */
    public function getTaxiMovementsForToday()
    {
        $currentDate = Carbon::now()->toDateString();

        // Query to get requests for the current day
        $taxiMovements = TaxiMovement::with(['customer.profile', 'movementType'])
            ->whereDate('created_at', $currentDate)
            ->where([
                'is_don' => false,
                'request_state' => 'pending'
            ])
            ->get()
            ->map(function ($taxiMovement) {
                return [
                    'id' => $taxiMovement->id,
                    'from' => $taxiMovement->my_address,
                    'to' => $taxiMovement->destination_address,
                    'gender' => $taxiMovement->gender,
                    'lat' => $taxiMovement->start_latitude,
                    'long' => $taxiMovement->start_longitude,
                    'avatar' => $taxiMovement->customer->profile->avatar ?? null,
                    'customer_name' => $taxiMovement->customer->profile->name ?? null,
                    'customer_phone' => $taxiMovement->customer->profile->phone_number ?? null,
                    'type' => $taxiMovement->movementType->type ?? null,
                    'time' => $taxiMovement->created_at,
                ];
            });

        return $taxiMovements;
    }

}

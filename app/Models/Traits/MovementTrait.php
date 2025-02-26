<?php

namespace App\Models\Traits;

use App\Enums\MovementRequestStatus;
use App\Enums\PaymentTypesEnum;
use App\Enums\UserEnums\UserGender;
use App\Models\Calculation;
use App\Models\CustomerMovementsCount;
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
        $mappedMovements = collect($movements)->map(function ($movement) {
            $movement_state = function ($movement) {
                $state = '';
                if ($movement->request_state == MovementRequestStatus::Accepted and !$movement->is_completed) {
                    $state = 'live';
                }

                if ($movement->request_state == MovementRequestStatus::Rejected) {
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
                'id' => $movement->id,
                'start_address' => $movement->start_address,
                'destination_address' => $movement->destination_address,
                'gender' => UserGender::getKey($movement->gender),
                'start_latitude' => $movement->start_latitude,
                'start_longitude' => $movement->start_longitude,
                'end_latitude' => $movement->end_latitude ?? null,
                'end_longitude' => $movement->end_longitude ?? null,
                'path' => json_decode($movement->path) ?? [],
                'driver_email' => $movement->driver->email ?? null,
                'customer_email' => $movement->customer->email,
                'driver_name' => $movement->driver->profile?->name ?? null,
                'driver_phone' => $movement->driver->profile?->phone_number ?? null,
                'customer_name' => $movement->customer->profile?->name ?? null,
                'customer_phone' => $movement->customer->profile?->phone_number ?? null,
                'customer_address' => $movement->customer->profile?->address ?? null,
                'taxi_id' => $movement->taxi_id ?? null,
                'car_name' => $movement->taxi->car_name ?? null,
                'car_lamp_number' => $movement->taxi->lamp_number ?? null,
                'car_plate_number' => $movement->taxi->plate_number ?? null,
                'type' => $movement->movement_type->type,
                'price' => $movement?->calculations->additional_amount ?? 0,
                'coin' => PaymentTypesEnum::getKey($movement?->calculations->coin ?? 0),
                'date' => $movement->created_at,
            ];
        });

        return $mappedMovements;
    }

    /**
     * Calculate amount paid for movements
     * @param TaxiMovement $movement
     * @param array $data
     * @return float|int|mixed
     */
    public static function calculateAmountPaid(TaxiMovement $taxiMovement, array $data)
    {
        $movement_type = $taxiMovement->movement_type;
//        if ($movement_type->is_onKM) {
//            $totalPrice = $data['distance'] * $movement_type->price1;
//        } else {
//            $totalPrice = $movement_type->price1;
//        }

        $totalPrice = $data['additional_amount'];
        $calculation = $taxiMovement->calculations()->create([
            'driver_id' => $taxiMovement->driver_id,
            'totalPrice' => $totalPrice,
            'distance' => $data['distance'],
            'additional_amount' => array_key_exists('additional_amount', $data) ? $data['additional_amount'] : null,
            'reason' => array_key_exists('reason', $data) ? $data['reason'] : null,
            'coin' => PaymentTypesEnum::getValue($data['coin'])
        ]);

        return $calculation;
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
        if ($user->customer_movements) {
            $todayCanceledMovements = $user->customer_movements?->filter(function ($movement) use ($today) {
                return $movement->is_canceled && $movement->created_at->isSameDay($today);
            })->count();
        } else {
            $todayCanceledMovements = 0;
        }

        if ($todayCanceledMovements >= 10) {
            $message = 'You have exceeded the allowed number of canceled movements for today.';
            send_notifications($user, $message, ['database', 'mail']);
            return api_response(message: $message, code: 429);
        }

        // Get the date ten days ago from today
        $tenDaysAgo = $today->copy()->subDays(10);

        if ($user->customer_movements) {
            // Filter movements for those that are canceled and created within the last ten days
            $lastTenDaysCanceledMovements = $user->customer_movements?->filter(function ($movement) use ($tenDaysAgo, $today) {
                return $movement->is_canceled && $movement->created_at->between($tenDaysAgo, $today);
            })->count();
        } else {
            $lastTenDaysCanceledMovements = 0;
        }
        if ($lastTenDaysCanceledMovements >= 30) {
            $user->is_active = false;
            $user->save();
            return api_response(message: 'تم إلغاء تنشيط الحساب بسبب الإلغاءات المفرطة خلال الأيام العشرة الماضية.', code: 423);
        }
    }

    /**
     * Get today Movement requests
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
     */
    public static function getLifeTaxiMovements()
    {
        $currentDate = Carbon::now()->toDateString();

        // Query to get requests for the current day
        return TaxiMovement::with(['customer.profile', 'movement_type'])
            ->whereDate('created_at', $currentDate)
            ->where([
                'is_redirected' => false,
                'is_canceled' => false,
                'request_state' => MovementRequestStatus::Pending
            ])
            ->get()
            ->map(function ($taxiMovement) {
                return (object)[
                    'id' => $taxiMovement->id,
                    'start_address' => $taxiMovement->start_address,
                    'destination_address' => $taxiMovement->destination_address,
                    'gender' => UserGender::getKey($taxiMovement->gender),
                    'start_latitude' => $taxiMovement->start_latitude,
                    'start_longitude' => $taxiMovement->start_longitude,
                    'avatar' => $taxiMovement->customer?->profile?->avatar ?? null,
                    'customer_name' => $taxiMovement->customer?->profile?->name ?? null,
                    'customer_phone' => $taxiMovement->customer?->profile?->phone_number ?? null,
                    'type' => $taxiMovement->movementType->type ?? null,
                    'time' => $taxiMovement->created_at,
                ];
            });
    }


    public function getSingleLifeTaxiMovementDetails()
    {
        // Query to get requests for the current day
        return (object)[
            'id' => $this->id,
            'start_address' => $this->start_address,
            'destination_address' => $this->destination_address,
            'gender' => UserGender::getKey($this->gender),
            'start_latitude' => $this->start_latitude,
            'start_longitude' => $this->start_longitude,
            'avatar' => $this->customer?->profile?->avatar ?? null,
            'customer_name' => $this->customer?->profile?->name ?? null,
            'customer_phone' => $this->customer?->profile?->phone_number ?? null,
            'type' => $this->movementType->type ?? null,
            'time' => $this->created_at,
        ];
    }


    /**
     * Increment customer movements numbers or create new record
     * @return void
     */
    public function incrementMovementCount(string $id)
    {
        $customer = User::find($id);
        if ($customer) {
            if ($customer->movementsCount) {
                $customer->movementsCount->increment('movements_count');
            } else {
                CustomerMovementsCount::create([
                    'customer_id' => $id,
                    'movements_count' => 1,
                ]);
            }
        }

    }
}

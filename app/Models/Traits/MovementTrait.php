<?php

namespace App\Models\Traits;

trait MovementTrait
{
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
}

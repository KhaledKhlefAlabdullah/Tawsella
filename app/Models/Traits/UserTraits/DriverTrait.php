<?php

namespace App\Models\Traits\UserTraits;

use App\Models\Movement;

trait DriverTrait
{
    public static function processMovementState(Movement $movement, int $state, string $message = null)
    {
        if ($movement->is_canceled) {
            return api_response(
                message: 'The request has already been canceled by the customer. We apologize for any inconvenience caused.',
                code: 410);
        } else {
            // Update the request state
            $movement->update([
                'request_state' => $state,
                'state_message' => $message
            ]);
        }
    }
}

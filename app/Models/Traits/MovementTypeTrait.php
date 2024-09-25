<?php

namespace App\Models\Traits;


use App\Enums\PaymentTypesEnum;

trait MovementTypeTrait
{
    /**
     * Mapping movements types for view
     * @param $movementTypes
     * @return mixed
     */
    public static function mappingMovementTypes($movementTypes)
    {
        return collect($movementTypes)->map(function ($type) {

            return [
                'id' => $type->id,
                'type' => $type->type,
                'description' => $type->description,
                'is_onKM' => $type->is_on_km,
                'price' => $type->price,
                'payment' => PaymentTypesEnum::getKey($type->payment),
                'is_general' => $type->is_general,
            ];
        });
    }

    /**
     * Mapping movement type for view
     * @param $movementTypes
     * @return mixed
     */
    public static function mappingSingleMovementType($movementType)
    {
        return [
            'id' => $movementType->id,
            'type' => $movementType->type,
            'description' => $movementType->description,
            'is_onKM' => $movementType->is_on_km,
            'price' => $movementType->price,
            'payment' => PaymentTypesEnum::getKey($movementType->payment),
            'is_general' => $movementType->is_general,
        ];
    }
}

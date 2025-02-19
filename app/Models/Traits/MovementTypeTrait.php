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
                'is_onKM' => $type->is_onKM,
                'price1' => $type->price1,
                'payment1' => PaymentTypesEnum::getKey($type->payment1),
                'price2' => $type->price2,
                'payment2' => PaymentTypesEnum::getKey($type->payment2),
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
            'is_onKM' => $movementType->is_onKM,
            'price1' => $movementType->price1,
            'payment1' => PaymentTypesEnum::getKey($movementType->payment1),
            'price2' => $movementType->price2,
            'payment2' => PaymentTypesEnum::getKey($movementType->payment2),
            'is_general' => $movementType->is_general,
        ];
    }
}

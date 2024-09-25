<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use App\Models\Traits\MovementTypeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class TaxiMovementType extends Model
{
    use HasFactory, HasUuid, MovementTypeTrait;

    protected $keyType = 'string';
    protected $primaryKey = 'id';
    public $incrementing = false;

    protected $fillable = [
        'type',
        'description',
        'is_onKM',
        'price',
        'payment',
        'is_general'
    ];

    public function type_movements()
    {
        return $this->hasMany(TaxiMovement::class, 'movement_type_id');
    }

    public function movement_type_offers()
    {
        return $this->hasMany(Offer::class, 'movement_type_id');
    }
}

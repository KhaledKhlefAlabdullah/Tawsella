<?php

namespace App\Models;

use App\Models\Traits\CalculationTrait;
use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Calculation extends Model
{
    use HasFactory, HasUuid, SoftDeletes, CalculationTrait;

    protected $keyType = 'string';
    protected $primaryKey = 'id';
    public $incrementing = false;

    protected $fillable = [
        'driver_id',
        'taxi_movement_id',
        'totalPrice',
        'distance',
        'additional_amount',
        'reason',
        'is_bring',
        'coin'
    ];

    public function driver(){
        return $this->belongsTo(User::class,'driver_id');
    }

    public function taxiMovement(){
        return $this->belongsTo(TaxiMovement::class,'taxi_movement_id');
    }
}

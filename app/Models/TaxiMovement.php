<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class TaxiMovement extends Model
{
    use HasFactory, HasUuid ,SoftDeletes;

    protected $keyType = 'string';
    protected $primaryKey = 'id';
    public $incrementing = false;

    protected $fillable = [
        'driver_id',
        'customer_id',
        'taxi_id',
        'movement_type_id',
        'my_address',
        'destnation_address',
        'gender',
        'start_latitude',
        'start_longitude',
        'end_latitude',
        'end_longitude',
        'is_don',
        'is_completed',
        'is_canceled',
        'request_state',
        'total_price_for_this_movement'
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function taxi()
    {
        return $this->belongsTo(Taxi::class, 'taxi_id');
    }

    public function movement_type()
    {
        return $this->belongsTo(TaxiMovementType::class, 'movement_type_id');
    }

    public function calculations(){
        return $this->hasMany(calculations::class,'taxi_movement_id');
    }
}

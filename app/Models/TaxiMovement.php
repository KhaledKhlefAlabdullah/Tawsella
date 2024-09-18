<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use App\Models\Traits\MovementTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class TaxiMovement extends Model
{
    use HasFactory, HasUuid ,SoftDeletes, MovementTrait;

    protected $keyType = 'string';
    protected $primaryKey = 'id';
    public $incrementing = false;

    protected $fillable = [
        'driver_id',
        'customer_id',
        'taxi_id',
        'movement_type_id',
        'start_address',
        'destination_address',
        'gender',
        'start_latitude',
        'start_longitude',
        'end_latitude',
        'end_longitude',
        'path',
        'is_redirected',
        'is_completed',
        'is_canceled',
        'state_message',
        'request_state',
        'total_price_for_this_movement'
    ];

    public function addPointToPath($latitude, $longitude)
    {
        $newPoint = ['longitude' => $longitude, 'latitude' => $latitude];

        if ($this->path) {
            // Decode the existing path from JSON
            $path = json_decode($this->path, true);

            // Append the new point to the path
            $path[] = $newPoint;

            // Encode the path back to JSON
            $this->path = json_encode($path);
        } else {
            // No path exists, create a new path with the new point
            $this->path = json_encode([$newPoint]);
        }

        // Save the updated model
        $this->save();
    }

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
        return $this->hasMany(Calculation::class,'taxi_movement_id');
    }
}

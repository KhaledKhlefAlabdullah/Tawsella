<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use App\Models\Traits\MovementTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

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

    // todo must to use it in update driver location
    public function addPointToPath($latitude, $longitude)
    {
        // Wrap in transaction to ensure atomicity
        DB::transaction(function () use ($latitude, $longitude) {
            // New point to be added
            $newPoint = ['longitude' => $longitude, 'latitude' => $latitude];

            // Initialize the path array
            $path = [];

            if ($this->path) {
                // Decode the existing path
                $decodedPath = json_decode($this->path, true);

                // Handle potential JSON decoding error
                if (json_last_error() === JSON_ERROR_NONE) {
                    $path = $decodedPath;
                } else {
                    // Log an error or throw an exception if necessary
                    throw new \Exception('Failed to decode path JSON: ' . json_last_error_msg());
                }
            }

            // Append the new point to the path
            $path[] = $newPoint;

            // Save the updated path in JSON format
            $this->path = json_encode($path);

            // Save the updated model
            $this->save();
        });
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

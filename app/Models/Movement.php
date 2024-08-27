<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use App\Traits\MovementTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Movement extends Model
{
    use HasFactory, HasUuid, SoftDeletes, MovementTrait;

    protected $keyType = 'string';
    protected $primaryKey = 'id';
    public $incrementing = false;

    protected $fillable = [
        'driver_id',
        'customer_id',
        'start_address',
        'destination_address',
        'start_latitude',
        'start_longitude',
        'end_latitude',
        'end_longitude',
        'path',
        'is_completed',
        'is_canceled',
        'request_state',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

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

}

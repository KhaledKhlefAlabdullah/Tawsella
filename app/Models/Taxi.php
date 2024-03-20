<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Taxi extends Model
{
    use HasFactory,HasUuid,SoftDeletes;

    protected $keyType = 'string';
    protected $primaryKey = 'id';
    public $incrementing = false;

    protected $fillable =[
        'driver_id',
        'car_name',
        'lamp_number',
        'plate_number',
        'car_detailes'
    ];

    public function driver(){
        return $this->belongsTo(User::class,'driver_id');
    }

    public function taxi_movements(){
        return $this->hasMany(TaxiMovement::class,'taxi_id');
    }
}

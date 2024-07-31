<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    use HasFactory, HasUuid, SoftDeletes;

    protected $keyType = 'string';
    protected $primaryKey = 'id';
    public $incrementing = false;

    protected $fillable = [
        'driver_id',
        'plat_number',
        'vehicle_image',
        'vehicle_description'
    ];

    public function driver(){
        return $this->belongsTo(User::class, 'user_id');
    }

}

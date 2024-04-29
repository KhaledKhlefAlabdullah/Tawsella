<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Rating extends Model
{
    use HasFactory,HasUuid,SoftDeletes;

    protected $keyType = 'string';
    protected $primaryKey = 'id';
    public $incrementing = false;

    protected $fillable =[
        'customer_id',
        'driver_id',
        'rating',
        'notes'
    ];

    public function customer_rating(){
        return $this->belongsTo(User::class,'customer_id');
    }

    public function driver_rating(){
        return $this->belongsTo(User::class,'driver_id');
    }
}

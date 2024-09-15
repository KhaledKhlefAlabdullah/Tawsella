<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use App\Models\Traits\OfferTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Offer extends Model
{
    use HasFactory,HasUuid,OfferTrait;

    protected $keyType = 'string';
    protected $primaryKey = 'id';
    public $incrementing = false;

    protected $fillable =[
        'movement_type_id',
        'admin_id',
        'offer',
        'value_of_discount',
        'valid_date',
        'description'
    ];

    public function movement_type_offer(){
        return $this->belongsTo(TaxiMovementType::class,'movement_type_id');
    }

    public function admin_offer(){
        return $this->belongsTo(User::class,'admin_id');
    }

}

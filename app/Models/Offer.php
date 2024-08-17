<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use App\Models\Traits\OffersTrate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Offer extends Model
{
    use HasFactory,HasUuid,SoftDeletes, OffersTrate;

    protected $keyType = 'string';
    protected $primaryKey = 'id';
    public $incrementing = false;

    protected $fillable =[
        'user_id',
        'title',
        'valid_date',
        'description'
    ];

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
}

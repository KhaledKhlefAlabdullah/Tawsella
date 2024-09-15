<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class ContactUsMessage extends Model
{
    use HasFactory,HasUuid;

    protected $keyType = 'string';
    protected $primaryKey = 'id';
    public $incrementing = false;

    protected $fillable =[
        'admin_id',
        'sender_name',
        'message',
        'email',
        'phone_number',
        'is_registeredInApp',
        'is_answered'
    ];

    public function admin(){
        return $this->belongsTo(User::class,'admin_id');
    }

}

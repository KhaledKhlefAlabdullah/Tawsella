<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class ContactUsMessage extends Model
{
    use HasFactory,HasUuid,SoftDeletes;

    protected $keyType = 'string';
    protected $primaryKey = 'id';
    public $incrementing = false;

    protected $fillable =[
        'admin_id',
        'description',
        'email',
        'phone_number',
        'is_answerd'
    ];

    public function admin(){
        return $this->belongsTo(User::class,'admin_id');
    }

}
//Symfony\Component\ErrorHandler\Error\FatalError: Trait "App\Models\Traits\HasUuid" not found in file D:\SmartCode Projects\star-taxi-12\star-taxi\app\Models\User.php on line 14

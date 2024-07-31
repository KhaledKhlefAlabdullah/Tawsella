<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class OurService extends Model
{
    use HasFactory,HasUuid;

    protected $keyType = 'string';
    protected $primaryKey = 'id';
    public $incrementing = false;

    protected $fillable =[
        'admin_id',
        'name',
        'description',
        'image',
        'logo',
    ];

    public function admin(){
        return $this->belongsTo(User::class,'admin_id');
    }

}

<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Advertisement extends Model
{
    use HasFactory,HasUuid;

    protected $keyType = 'string';
    protected $primaryKey = 'id';
    public $incrementing = false;

    protected $fillable =[
        'admin_id',
        'title',
        'description',
        'image',
        'logo',
        'validity_date'
    ];

    public function admin(){
        return $this->belongsTo(User::class,'admin_id');
    }

}

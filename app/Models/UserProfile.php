<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class UserProfile extends Model
{
    use HasFactory,HasUuid,SoftDeletes;

    protected $keyType='string';

    protected $primaryKey='id';

    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'avatar',
        'phone_number',
        'gender'
    ];

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
}

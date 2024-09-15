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
        'phoneNumber',
        'gender'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($profile) {
            if (empty($profile->avatar)) {
                $profile->avatar = $profile->gender == 'male'
                    ? '/images/profile_images/man.png'
                    : '/images/profile_images/woman.png';
            }
        });
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
}

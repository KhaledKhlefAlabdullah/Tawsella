<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasUuid, SoftDeletes;

    protected $keyType='string';

    protected $primaryKey='id';

    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
        'user_type',
        'phone_number',
        'driver_state',
        'is_active'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function user_profile(){
        return $this->hasOne(UserProfile::class,'user_id');
    }

    public function customer_ratings(){
        return $this->hasMany(Rating::class,'customer_id');
    }

    public function driver_ratings(){
        return $this->hasMany(Rating::class,'driver_id');
    }

    public function taxi(){
        return $this->hasOne(Taxi::class,'driver_id');
    }

    public function customer_movements(){
        return $this->hasMany(TaxiMovement::class,'customer_id');
    }

    public function driver_movements(){
        return $this->hasMany(TaxiMovement::class,'driver_id');
    }

    public function admin_offers(){
        return $this->hasMany(Offer::class,'admin_id');
    }

    public function about_us(){
        return $this->hasOne(AboutUs::class,'admin_id');
    }

    public function contact_us_messages(){
        return $this->hasMany(ContactUsMessage::class,'admin_id');
    }

    public function our_services(){
        return $this->hasMany(OurService::class,'admin_id');
    }

    public function calculations(){
        return $this->hasMany(calculations::class,'driver_id');
    }
}

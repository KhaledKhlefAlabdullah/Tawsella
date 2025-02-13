<?php

namespace App\Models;

use App\Enums\UserEnums\DriverState;
use App\Interfaces\IMustVerifyEmailByCode;
use App\Models\Traits\HasUuid;
use App\Models\Traits\UserTraits\AdminTrait;
use App\Models\Traits\UserTraits\CustomerTrait;
use App\Models\Traits\UserTraits\DriverTrait;
use App\Models\Traits\UserTraits\MustVerifyEmailByCode;
use App\Models\Traits\UserTraits\UserTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements IMustVerifyEmailByCode
{
    use HasApiTokens, HasFactory, Notifiable, HasUuid, HasRoles, UserTrait, DriverTrait, CustomerTrait, AdminTrait;

    use MustVerifyEmailByCode;

    protected $keyType = 'string';

    protected $primaryKey = 'id';

    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
        'device_token',
        'driver_state',
        'is_active',
        'mail_verify_code',
        'mail_code_verified_at',
        'mail_code_attempts_left',
        'mail_code_last_attempt_date',
        'mail_verify_code_sent_at',
        'rating'
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

    protected static function boot()
    {
        parent::boot();

        static::retrieved(function ($driver) {
            if ($driver->driver_state === DriverState::Ready) {
                $driver->driver_state = 'Ready';
            } elseif ($driver->driver_state === DriverState::InBreak) {
                $driver->driver_state = 'InBreak';
            } elseif ($driver->driver_state === DriverState::Reserved) {
                $driver->driver_state = 'Reserved';
            }
        });
    }


    public function profile()
    {
        return $this->hasOne(UserProfile::class, 'user_id');
    }

    public function customer_ratings()
    {
        return $this->hasMany(Rating::class, 'customer_id');
    }

    public function driver_ratings()
    {
        return $this->hasMany(Rating::class, 'driver_id');
    }

    public function taxi()
    {
        return $this->hasOne(Taxi::class, 'driver_id');
    }

    public function customer_movements()
    {
        return $this->hasMany(TaxiMovement::class, 'customer_id');
    }

    public function driver_movements()
    {
        return $this->hasMany(TaxiMovement::class, 'driver_id');
    }

    public function admin_offers()
    {
        return $this->hasMany(Offer::class, 'admin_id');
    }

    public function about_us()
    {
        return $this->hasOne(AboutUs::class, 'admin_id');
    }

    public function contact_us_messages()
    {
        return $this->hasMany(ContactUsMessage::class, 'admin_id');
    }

    public function our_services()
    {
        return $this->hasMany(Advertisement::class, 'admin_id');
    }

    public function calculations()
    {
        return $this->hasMany(Calculation::class, 'driver_id');
    }

    public function movementsCount()
    {
        return $this->hasOne(CustomerMovementsCount::class, 'customer_id');
    }
}

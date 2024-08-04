<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Traits\HasUuid;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use App\Interfaces\MustVerifyEmailByCode as IMustVerifyEmailByCode;
use App\Models\Traits\MustVerifyEmailByCode;

class User extends Authenticatable implements IMustVerifyEmailByCode
{
    use HasApiTokens, HasFactory, Notifiable, HasUuid, SoftDeletes;

    use MustVerifyEmailByCode;

    protected $keyType = 'string'; // Specifies the data type of the primary key

    protected $primaryKey = 'id'; // Sets the primary key for the User model

    public $incrementing = false; // Indicates that the primary key is not auto-incrementing

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
        'user_type',
        'driver_state',
        'is_active',
        'last_location_latitude',
        'last_location_longitude',
        'mail_verify_code',
        'mail_code_verified_at',
        'mail_code_attempts_left',
        'mail_code_last_attempt_date',
        'mail_verify_code_sent_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password', // Ensures the password is not visible when the user model is serialized
        'remember_token', // Ensures the remember token is not visible when the user model is serialized
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime', // Casts 'email_verified_at' to a datetime object
        'password' => 'hashed', // Ensures the password is hashed
    ];

    // Relationships
    public function profile()
    {
        return $this->hasOne(UserProfile::class, 'user_id'); // Defines a one-to-one relationship with UserProfile
    }

    public function customer_ratings()
    {
        return $this->hasMany(Rating::class, 'customer_id'); // Defines a one-to-many relationship with Rating for customers
    }

    public function driver_ratings()
    {
        return $this->hasMany(Rating::class, 'driver_id'); // Defines a one-to-many relationship with Rating for drivers
    }

    public function customer_movements()
    {
        return $this->hasMany(Movement::class, 'customer_id'); // Defines a one-to-many relationship with Movement for customers
    }

    public function driver_movements()
    {
        return $this->hasMany(Movement::class, 'driver_id'); // Defines a one-to-many relationship with Movement for drivers
    }

    public function admin_offers()
    {
        return $this->hasMany(Offer::class, 'admin_id'); // Defines a one-to-many relationship with Offer for admins
    }

    public function about_us()
    {
        return $this->hasOne(AboutUs::class, 'admin_id'); // Defines a one-to-one relationship with AboutUs for admins
    }

    public function contact_us_messages()
    {
        return $this->hasMany(ContactUsMessage::class, 'admin_id'); // Defines a one-to-many relationship with ContactUsMessage for admins
    }

    public function our_services()
    {
        return $this->hasMany(OurService::class, 'admin_id'); // Defines a one-to-many relationship with OurService for admins
    }

    public function vehicle()
    {
        return $this->hasOne(Vehicle::class, 'user_id'); // Defines a one-to-one relationship with Vehicle
    }

    public function chats()
    {
        return $this->belongsToMany(Chat::class,'chat_members','member_id','chat_id'); // Defines a many-to-many relationship with Chat
    }

    public function sendedMessages()
    {
        return $this->hasMany(Message::class,'sender_id'); // Defines a one-to-many relationship with Message for sent messages
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class,'receiver_id'); // Defines a one-to-many relationship with Message for received messages
    }

    // Relationship to Message model for the messages that starred by the user
    public function starredMessages(){
        return $this->belongsToMany(Message::class, 'user_starred_messages');
    }
}

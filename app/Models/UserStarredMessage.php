<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserStarredMessage extends Model
{
    use HasFactory, HasUuid; // Traits to enable factories, UUIDs as primary keys, and soft deletion

    protected $keyType = 'string'; // Specifies the type of the primary key
    protected $primaryKey = 'id'; // Specifies the primary key field
    public $incrementing = false; // Indicates that the primary key is not auto-incrementing

    // Mass assignable attributes
    protected $fillable = [
        'user_id',      // Foreign key for the chat
        'message_id'    // Foreign key for the sender user

    ];

}

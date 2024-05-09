<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Represents a member within a chat in the database.
 */
class ChatMember extends Model
{
    use HasFactory, HasUuid, SoftDeletes; // Enable factory methods, UUIDs as primary keys, and soft deletion capabilities.

    protected $keyType = 'string'; // Specifies that the primary key type is a string.
    protected $primaryKey = 'id'; // Sets the primary key for the model.
    public $incrementing = false; // Indicates that the primary key is not auto-incrementing.

    protected $fillable = [
        'chat_id', // ID of the chat this member belongs to.
        'member_id' // ID of the member in the chat.
    ];
}

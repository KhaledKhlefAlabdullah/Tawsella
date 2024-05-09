<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// Message model which uses UUIDs, soft deletes, and factory patterns
class Message extends Model
{
    use HasFactory, HasUuid, SoftDeletes; // Traits to enable factories, UUIDs as primary keys, and soft deletion

    protected $keyType = 'string'; // Specifies the type of the primary key
    protected $primaryKey = 'id'; // Specifies the primary key field
    public $incrementing = false; // Indicates that the primary key is not auto-incrementing

    // Mass assignable attributes
    protected $fillable = [
        'chat_id',      // Foreign key for the chat
        'sender_id',    // Foreign key for the sender user
        'receiver_id',  // Foreign key for the receiver user
        'message',      // Text content of the message
        'image_url',    // URL to an image if attached
        'voice_url',    // URL to a voice message if attached
        'is_edited',    // Boolean flag for edited messages
        'is_stared'     // Boolean flag for starred messages
    ];

    // Relationship to Chat model
    public function chat()
    {
        return $this->belongsTo(Chat::class,'chat_id');
    }

    // Relationship to User model for the sender
    public function sender()
    {
        return $this->belongsTo(User::class,'sender_id');
    }

    // Relationship to User model for the receiver
    public function receiver()
    {
        return $this->belongsTo(User::class,'receiver_id');
    }
}

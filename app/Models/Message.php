<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use App\Models\Traits\MessagesTrate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use HasFactory, HasUuid, SoftDeletes, MessagesTrate;

    protected $keyType = 'string';
    protected $primaryKey = 'id';
    public $incrementing = false;

    // Mass assignable attributes
    protected $fillable = [
        'chat_id',
        'sender_id',
        'message',
        'media',
        'is_edited',
    ];

    // Relationship to Chat model
    public function chat()
    {
        return $this->belongsTo(Chat::class, 'chat_id');
    }

    // Relationship to User model for the sender
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    // Relationship to User model for the receiver
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    // Relationship to User model for the users who starred this message
    public function usersStarredMessage()
    {
        return $this->belongsToMany(User::class, 'user_starred_messages');
    }


}

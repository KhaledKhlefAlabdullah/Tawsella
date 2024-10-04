<?php

namespace App\Models;

use App\Models\Traits\ChatsTrait;
use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chat extends Model
{
    use HasFactory, HasUuid, SoftDeletes, ChatsTrait;

    protected $keyType = 'string';
    protected $primaryKey = 'id';
    public $incrementing = false;

    protected $fillable = [
        'name'
    ];

    public function messages()
    {
        return $this->hasMany(Message::class, 'chat_id');
    }


    public function members()
    {
        return $this->belongsToMany(User::class, 'chat_members', 'chat_id', 'member_id');
    }
}

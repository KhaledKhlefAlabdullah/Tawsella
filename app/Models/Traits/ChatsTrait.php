<?php

namespace App\Models\Traits;

use App\Models\Chat;
use App\Models\ChatMember;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Support\Str;

trait ChatsTrait
{
    /**
     * Create chat between users
     * @param User $receiver
     * @return \Illuminate\Http\JsonResponse|void
     */
    public static function CreateChatBetweenUserAndDriver(User $receiver, User $driver = null){
        if (!$receiver) {
            return api_response(message: 'This user not found or there problem in his id', code: 404);
        }

        $userName = $driver ? $driver->profile->name : UserProfile::where('user_id', getMyId())->value('name');
        $receiverName = $receiver->profile?->name;

        // Generate chat names based on username
        $chatName = 'chat-between-' . $userName . '-and-' . $receiverName;
        $chatNameR = 'chat-between-' . $receiverName . '-and-' . $userName;

        // Check if the chat already exists, if not create a new one
        $chat = Chat::where('name', $chatName)->orWhere('name', $chatNameR)->first();
        if (!$chat) {
            $chat = Chat::create([
                'name' => 'chat-between-' . $userName . '-and-' . $receiverName,
            ]);

            ChatMember::create([
                'chat_id' => $chat->id,
                'member_id' => getMyId(),
            ]);

            ChatMember::create([
                'chat_id' => $chat->id,
                'member_id' => $receiver->id,
            ]);
        }
    }
}

<?php

namespace App\Models\Traits;

trait MessagesTrate
{
    // Mapping the message to one shape for view
    public static function mapingMessages($messages)
    {
        return $messages->map(function ($message) {
            $sender = $message->sender;
            $profile = $sender?->profile;
            $is_messageStarred = $message->usersStarredMessage->contains(getMyId()) == 1;

            // mapping the data to this shape
            return [
                'sender_id' => $sender?->id,
                'sender_name' => $profile?->name,
                'sender_avatar' => $profile?->avatar,
                'message_id' => $message->id,
                'message' => $message->message,
                'media' => $message->media,
                'created_at' => $message->created_at,
                'is_edited' => $message->is_edited == 1,
                'is_starred' => $is_messageStarred
            ];
        });
    }
}

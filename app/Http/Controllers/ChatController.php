<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\ChatMember;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    /**
     * Display a listing of chats i member in
     * @return JsonResponse list of chats i memeber in
     */
    public function index()
    {
        try {
// Get chat IDs for the current user
            $chatIds = ChatMember::where('member_id', getMyId())
                ->pluck('chat_id');

// Retrieve chats where the user is a member
            $chats = Chat::whereIn('id', $chatIds)
                ->get()
                ->map(function ($chat) {
                    $messages = $chat->members->filter(function ($member) {
                        return $member->id !== getMyId();
                    })->map(function ($member) use ($chat) {

                        $lastMessage = $chat->messages->sortByDesc('created_at')->first();

                        return [
                            'chat_id' => $chat->id,
                            'receiver_id' => $member->id,
                            'receiver_name' => $member->profile->name ?? null,
                            'receiver_avatar' => $member->profile->avatar ?? null,
                            'message' => $lastMessage->message ? $lastMessage->message : 'received media',
                            'created_at' => $lastMessage->created_at ?? null,
                            'is_edited' => $lastMessage->is_edited ?? null,
                        ];
                    });

                    return $messages;
                });

            // Return response with formatted chat data
            return api_response(data: $chats, message: 'تم جلب بيانات المحادثات بنجاح');
        } catch (Exception $e) {
            return api_response(errors: [$e->getMessage()], message: 'هناك مشكلة في جلب بيانات المحادثات', code: 500);
        }
    }

    /**
     * Store a newly chat to database
     * @param User $receiver is user i want to send message to
     * @return JsonResponse response with success or faild message
     */
    public function store(User $receiver)
    {
        try {

            if (!$receiver) {
                return api_response(message: 'هذا المستخدم غير موجود لدينا أو يوجد مشكلة في معرفه', code: 404);
            }

            $userName = User::where('user_id', getMyId())->value('name');
            $receiverName = $receiver->profile->name;

            // Generate chat names based on user names
            $chatName = 'chat-between-' . $userName . '-and-' . $receiverName;
            $chatNameR = 'chat-between-' . $receiverName . '-and-' . $userName;

            // Check if the chat already exists, if not create a new one
            $chat = Chat::where('name', $chatName)->orWhere('name', $chatNameR)->first();
            if (!$chat) {
                $chat = Chat::create([
                    'name' => 'chat-between-' . $userName . '-and-' . $receiverName,
                ]);

                $chat->members()->attach([$receiver->id, getMyId()]);
            }
            return api_response(message: 'تم إنشاء محادثة بنجاح');
        } catch (Exception $e) {
            return api_response(message: 'هناك مشكلة في إنشاء محادثة', code: 500);
        }
    }

    /**
     * Remove chat from database
     * @param Chat $chat is chat i wnat to delete it
     * @return JsonResponse response with success or faild message
     */
    public function destroy(Chat $chat)
    {
        try {
            if (!$chat) {
                return api_response(message: 'لم يتم إيجاد الكائن', code: 404);
            }
            $chat->delete();

            return api_response(message: 'تم حذف المحادثة بنجاح');
        } catch (Exception $e) {
            return api_response(errors: [$e->getMessage()], message: 'هناك خطأ في حذف المحادثة', code: 500);
        }
    }
}

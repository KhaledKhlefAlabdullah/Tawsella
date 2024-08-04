<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\ChatMember;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    /**
     * Display a listing of chats i member in
     * @return mixed list of chats i memeber in
     */
    public function index()
    {
        try {
            // Get chat IDs for the current user
            $chat_ids = ChatMember::where('member_id', getMyId())
                ->pluck('chat_id');

            // Retrieve chats where the user is a member and join with user profiles
            $chats = Chat::whereIn('chats.id', $chat_ids)
                ->join('chat_members as cm', 'chats.id', '=', 'cm.chat_id')
                ->join('user_profiles as up', 'cm.member_id', '=', 'up.user_id')
                ->leftJoin('messages as m', 'chats.id', '=', 'm.chat_id')
                ->select('chats.id as chat_id', 'cm.member_id as receiver_id', 'up.name as receiver_name', 'up.avatar as receiver_avatar')
                ->selectRaw('GROUP_CONCAT(m.message) as messages')
                ->selectRaw('GROUP_CONCAT(m.image_url) as image_urls')
                ->selectRaw('GROUP_CONCAT(m.voice_url) as voice_urls')
                ->selectRaw('GROUP_CONCAT(m.created_at) as created_ats')
                ->where('cm.member_id', '<>', getMyId()) // Use '<>' for 'not equal'
                ->groupBy('chats.id', 'cm.member_id', 'up.name', 'up.avatar') // Use actual column names here
                ->get();

            // Format the results
            $chats = $chats->map(function ($item) {
                $messages = collect(explode(',', $item->messages))->map(function ($message, $index) use ($item) {
                    return [
                        'message' => $message,
                        'image_url' => explode(',', $item->image_urls)[$index] ?? null,
                        'voice_url' => explode(',', $item->voice_urls)[$index] ?? null,
                        'created_at' => explode(',', $item->created_ats)[$index] ?? null
                    ];
                });

                return [
                    'chat_id' => $item->chat_id,
                    'receiver_id' => $item->receiver_id,
                    'receiver_name' => $item->receiver_name,
                    'receiver_avatar' => $item->receiver_avatar,
                    'messages' => $messages
                ];
            });

            //    ( reciver name - reciver avatar - last message - last message created_at)
            return api_response(data: $chats, message: 'تم جلب بيانات المحادثات بنجاح');
        } catch (Exception $e) {
            return api_response(errors: [$e->getMessage()], message: 'هناك مشكلة في جلب بيانات المحادثات', code: 500);
        }
    }

    /**
     * Store a newly chat to database
     * @param string $receiver_id is user id i want to send message to
     * @return mixed response with success or faild message
     */
    public function store(string $receiver_id)
    {
        try {
            // Get the receiver user by ID
            $receiver = getAndCheckModelById(User::class, $receiver_id);

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
     * @param string $id is chat is id i wnat to delete it
     * @return mixed response with success or faild message
     */
    public function destroy(string $id)
    {
        try {

            $chat = getAndCheckModelById(Chat::class, $id);

            $chat->delete();

            return api_response(message: 'تم حذف المحادثة بنجاح');
        } catch (Exception $e) {
            return api_response(errors: [$e->getMessage()], message: 'هناك خطأ في حذف المحادثة', code: 500);
        }
    }
}

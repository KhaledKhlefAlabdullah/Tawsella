<?php

namespace App\Http\Controllers;

use App\Events\SendMessageEvent;
use App\Http\Requests\Messages\SendMessageRequest;
use App\Models\Chat;
use App\Models\Message;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{

    private $QUERY = [
        'messages.id as message_id',
        'up.name as sender_name',
        'up.avatar as sender_avatar',
        'messages.message',
        'messages.media',
        'messages.is_edited',
        'messages.is_stared',
        'messages.created_at'
    ];
    /**
     * Display a listing of the resource.
     * @param string $chat_id To get the messages belongs to this chat
     * @return JsonResponse with messages data and status code 200 if success or with errors in failed
     */
    public function index(string $chat_id)
    {
        try {

            if (!getAndCheckModelById(Chat::class, $chat_id)) {
                return api_response(message: 'يبدو أن هنالك مشكلة في معرف المحادثة حاول مرة اخرى', code: 404);
            }
            //  message(text or Audio or image) -  - receiver name - created_at - is_edited - is_stared )

            $messages = Message::where('chat_id', $chat_id)
                ->join('user_profiles as up', 'messages.sender_id', '=', 'up.user_id')
                ->select(
                    $this->QUERY
                )
                ->orderBy('messages.created_at', 'desc')
                ->get();

            return api_response(data: $messages, message: 'تم جلب بيانات الرسائل بنجاح');
        } catch (Exception $e) {
            return api_response(errors: [$e->getMessage()], message: 'هناك مشكلة في جلب بيانات الرسائل', code: 500);
        }
    }


    /**
     * @param string $chat_id is optional parameter if is set will return just starred messages belong to this chat
     * @return JsonResponse with starred messages data and status code 200 if success or with errors in failed
     */
    public function getStarredMessages(string $chat_id = null)
    {
        try {
            // get auth user
            $user = Auth::user();
            // if the chat_id is null get all starred messages for this user else get just starred messages in on chat
            $starredMessages = is_null($chat_id) ? $user->starredMessages : $user->starredMessages->where('chat_id', $chat_id);

            return api_response(data: $starredMessages, message: 'تم جلب الرسائل المميزة بنجمة بنجاح');
        } catch (Exception $e) {
            return api_response(errors: [$e->getMessage()], message: 'هناك خطأ في جلب الرسائل المميزة بنجمة', code: 500);
        }
    }



    /**
     * @param SendMessageRequest $request is new message data with (chat_id - sender_id - receiver_id - message:nullable - media:nullable)
     * @return JsonResponse with starred messages data and status code 200 if success or with errors in failed
     */
    public function store(SendMessageRequest $request)
    {
        try {

            $validatedData = $request->validated();

            if ($request->has('media')) {
                $validatedData['media'] = storeFile($validatedData['media'], 'messages/' . $validatedData['chat_id']);
            }
            // $validatedData['created_at'] = now();
            $message = Message::create($validatedData);

            $profile = Auth::user()->profile;

            SendMessageEvent::dispatch([
                'message_id' => $message->id,
                'sender_name' => $profile->name,
                'sender_avatar' => $profile->avatar,
                'message' => $message->message,
                'media' => $message->media,
                'is_edited' => $message->is_edited,
                'is_stared' => $message->is_stared,
                'created_at' => $message->created_at
            ], $validatedData['receiver_id']);

            return api_response(message: 'تم ارسال الرسالة بنجاح');
        } catch (Exception $e) {
            return api_response(errors: [$e->getMessage()], message: 'هناك مشكلة في إرسال الرسالة', code: 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Message $message)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Message $message)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Message $message)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Message $message)
    {
        //
    }
}

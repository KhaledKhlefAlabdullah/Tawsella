<?php

namespace App\Http\Controllers;

use App\Events\Messages\UpdateMessageEvent;
use App\Events\Messages\SendMessageEvent;
use App\Http\Requests\Messages\EditMessageRequest;
use App\Http\Requests\Messages\SendMessageRequest;
use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
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
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-27
     * @param string $chat_id To get the messages belongs to this chat
     * @return JsonResponse with messages data and status code 200 if success or with errors in failed
     */
    public function index(string $chat_id)
    {
        try {

            // chcek if there chat with this id
            if (!getAndCheckModelById(Chat::class, $chat_id)) {
                return api_response(message: 'يبدو أن هنالك مشكلة في معرف المحادثة حاول مرة اخرى', code: 404);
            }
            //  message(text or Audio or image) -  - receiver name - created_at - is_edited - is_stared )

            $messages = Message::where('chat_id', $chat_id)
                ->join('user_profiles as up', 'messages.sender_id', '=', 'up.user_id')
                ->select($this->QUERY)
                ->orderBy('messages.created_at', 'desc')
                ->get();

            return api_response(data: $messages, message: 'تم جلب بيانات الرسائل بنجاح');
        } catch (Exception $e) {
            return api_response(errors: [$e->getMessage()], message: 'هناك مشكلة في جلب بيانات الرسائل', code: 500);
        }
    }


    /**
     * Get starred messages
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-28
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
     * store new message
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-29
     * @param SendMessageRequest $request is new message data with (chat_id - sender_id - receiver_id - message:nullable - media:nullable)
     * @return JsonResponse with success message and status code 200 if success or with errors in failed
     */
    public function store(SendMessageRequest $request)
    {
        try {

            // check data if valid
            $validatedData = $request->validated();

            if ($request->has('media')) {
                $validatedData['media'] = storeFile($validatedData['media'], 'messages/' . $validatedData['chat_id']);
            }
            // $validatedData['created_at'] = now();
            $message = Message::create($validatedData);

            // get the auth user profile
            $profile = Auth::user()->profile;

            // broadcast the message on realtime chat channel
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

            // Send Message notification
            $receiver = getAndCheckModelById(User::class, $message->receiver_id);
            $notificationMessage = is_null($message->message) ? __('media-receive') : $message->message;
            send_notifications($receiver, $notificationMessage);
            
            return api_response(message: 'تم ارسال الرسالة بنجاح');
        } catch (Exception $e) {
            return api_response(errors: [$e->getMessage()], message: 'هناك مشكلة في إرسال الرسالة', code: 500);
        }
    }

    /**
     * Set message as starred
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-30
     * @param string $id is message i want to starred
     * @return JsonResponse with success message and status code 200 if success or with errors in failed
     */
    public function setMessageStarred(string $id)
    {
        try {

            // check if there message with $id
            getAndCheckModelById(Message::class, $id);

            $user = Auth::user();
            $isStarred = $user->starredMessages()->where('message_id', $id)->exists();

            if ($isStarred) {
                // if the message is starred unstarred
                $user->starredMessages()->detach($id);
                $message = 'تم إلغاء تمييز الرسالة بنجمة بنجاح';
            } else {
                // else starred the message
                $user->starredMessages()->attach($id);
                $message = 'تم تمييز الرسالة بنجمة بنجاح';
            }

            return api_response(message: $message);
        } catch (Exception $e) {
            return api_response(errors: [$e->getMessage()], message: 'هناك خطا في تمييز الرسالة بنجمة', code: 500);
        }
    }

    /**
     * edit message details
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-31
     * @param EditMessageRequest $request is the new message data that i want to edit
     * @param string $id is message i want to update
     * @return JsonResponse with success message and status code 200 if success or with errors in failed
     */
    public function update(EditMessageRequest $request, string $id)
    {
        try {
            // check data if valid
            $validatedData = $request->validated();

            // chcek if there message with this id if is exists return the message
            $message = getAndCheckModelById(Message::class, $id);

            if ($request->has('media')) {
                $validatedData['media'] = editFile($message->media, $validatedData['media'], 'messages/' . $message->chat_id);
            }

            $message->update($validatedData);

            // broadcast the message after edit
            UpdateMessageEvent::dispatch($message);

            return api_response(message: 'تم تعديل الرسالة بنجاح');
        } catch (Exception $e) {
            return api_response(errors: [$e->getMessage()], message: 'هناك خطا في تعديل الرسالة', code: 500);
        }
    }

    /**
     * edit message details
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-31
     * @param string $id is message i want to delete
     * @return JsonResponse with success message and status code 200 if success or with errors in failed
     */
    public function destroy(string $id)
    {
        try {

            // chcek if there message with this id if is exists return the message
            $message = getAndCheckModelById(Message::class, $id);

            // remove message media file
            removeFile($message->media);

            // delete the messsage
            $message->delete();

            return api_response(message: 'تم حذف الرسالة بنجاح');
        } catch (Exception $e) {
            return api_response(errors: [$e->getMessage()], message: 'هناك خطا في حذف الرسالة', code: 500);
        }
    }
}

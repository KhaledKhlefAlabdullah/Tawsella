<?php

namespace App\Http\Controllers;

use App\Events\Messages\UpdateMessageEvent;
use App\Events\Messages\SendMessageEvent;
use App\Http\Requests\Messages\EditMessageRequest;
use App\Http\Requests\Messages\SendMessageRequest;
use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use App\Notifications\TawsellaNotification;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Mailer\Event\MessageEvent;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-27
     * @param Chat $chat To get the messages belongs to this chat
     * @return JsonResponse with messages data and status code 200 if success or with errors in failed
     */
    public function index(Chat $chat)
    {
        try {

            // get chat messages
            $messages = $chat->messages->sortByDesc('created_at');

            return api_response(data: Message::mapingMessages($messages), message: 'Successfully getting messages');
        } catch (Exception $e) {
            return api_response(errors: [$e->getMessage()], message: 'Getting messages error', code: 500);
        }
    }

    /**
     * Get all starred messages
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-28
     * @return JsonResponse with starred messages data and status code 200 if success or with errors in failed
     */
    public function getAllStarredMessages()
    {
        try {
            // get auth user
            $user = Auth::user();
            // if the chat is null get all starred messages for this user else get just starred messages in on chat
            $starredMessages = $user->starredMessages;

            return api_response(data: Message::mapingMessages($starredMessages), message: 'Successfully getting all starred messages');
        } catch (Exception $e) {
            return api_response(errors: [$e->getMessage()], message: 'Getting starred messages error', code: 500);
        }
    }

    /**
     * Get starred messages
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-28
     * @param Chat $chat to return just starred messages belong to this chat
     * @return JsonResponse with starred messages data and status code 200 if success or with errors in failed
     */
    public function getChatStarredMessages(Chat $chat)
    {
        try {
            // get auth user
            $user = Auth::user();
            // if the chat is null get all starred messages for this user else get just starred messages in on chat
            $starredMessages = $user->starredMessages->where('chat_id', $chat->id);

            return api_response(data: Message::mapingMessages($starredMessages), message: 'Successfully getting chat starred messages');
        } catch (Exception $e) {
            return api_response(errors: [$e->getMessage()], message: 'Getting starred messages error', code: 500);
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
            $receiver = getAndCheckModelById(User::class, $validatedData['receiver_id']);

            $notificationMessage = is_null($message->message) ? 'receive an media message' : $message->message;

            send_notifications($receiver, $notificationMessage);

            return api_response(message: 'Successfully sending message');
        } catch (Exception $e) {
            return api_response(errors: [$e->getMessage()], message: 'Sending message error', code: 500);
        }
    }

    /**
     * Set message as starred
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-30
     * @param Message $message is message i want to starred
     * @return JsonResponse with success message and status code 200 if success or with errors in failed
     */
    public function setMessageStarred(Message $message)
    {
        try {
            $user = Auth::user();
            $id = $message->id;
            $isStarred = $user->starredMessages()->where('message_id', $id)->exists();

            if ($isStarred) {
                // if the message is starred unstarred
                $user->starredMessages()->detach($id);
                $message = 'Successfully unstarred message';
            } else {
                // else starred the message
                $user->starredMessages()->attach($id);
                $message = 'Successfully starred message';
            }

            return api_response(message: $message);
        } catch (Exception $e) {
            return api_response(errors: [$e->getMessage()], message: 'Message starred error', code: 500);
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
    public function update(EditMessageRequest $request, Message $message)
    {
        try {
            // check data if valid
            $validatedData = $request->validated();

            if ($request->has('media')) {
                $validatedData['media'] = editFile($message->media, $validatedData['media'], 'messages/' . $message->chat_id);
            }

            $message->update($validatedData);

            // broadcast the message after edit
            broadcast(new UpdateMessageEvent($message));

            return api_response(message: 'Successfully editing message');
        } catch (Exception $e) {
            return api_response(errors: [$e->getMessage()], message: 'Editing message error', code: 500);
        }
    }

    /**
     * edit message details
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-31
     * @param Message $message is message i want to delete
     * @return JsonResponse with success message and status code 200 if success or with errors in failed
     */
    public function destroy(Message $message)
    {
        try {
            // remove message media file
            removeFile($message->media);

            // delete the messsage
            $message->delete();

            return api_response(message: 'Successfully deleting message');
        } catch (Exception $e) {
            return api_response(errors: [$e->getMessage()], message: 'Deleting message error', code: 500);
        }
    }
}

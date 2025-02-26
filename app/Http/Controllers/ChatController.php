<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\ChatMember;
use App\Models\User;
use App\Services\PaginationService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    protected $paginationService;

    public function __construct(PaginationService $paginationService)
    {
        $this->paginationService = $paginationService;
    }

    /**
     * Display a listing of chats i member in
     * @return JsonResponse list of chats i memeber in
     */
    public function index(Request $request)
    {
        // Get chat IDs for the current user
        $chatIds = ChatMember::where('member_id', getMyId())
            ->pluck('chat_id');

        // Retrieve chats where the user is a member
        $query = Chat::query()->whereIn('id', $chatIds);

        $chatsQ = $this->paginationService->applyPagination($query, $request);

        $chats = collect($chatsQ->items())->map(function ($chat) {
            // Ensure $chat->members is not null before filtering
            if ($chat->members) {
                $messages = $chat->members?->filter(function ($member) {
                    return $member?->id !== getMyId();
                })->map(function ($member) use ($chat) {
                    // Ensure $chat->messages is not null and has at least one message
                    $lastMessage = $chat->messages?->sortByDesc('created_at')->first();

                    return [
                        'chat_id' => $chat->id,
                        'receiver_id' => $member?->id ?? null,
                        'receiver_name' => $member?->profile?->name ?? null,
                        'receiver_avatar' => $member?->profile?->avatar ?? null,
                        'message' => $lastMessage?->message ? $lastMessage->message : 'received media',
                        'created_at' => $lastMessage?->created_at ?? null,
                        'is_edited' => $lastMessage?->is_edited ?? null,
                    ];
                });

                return $messages;
            } else {
                return collect();
            }
        });

        // Return response with formatted chat data
        return api_response(data: $chats, message: 'تم جلب المحادثات بنجاح', pagination: get_pagination($chatsQ, $request));
    }

    /**
     * Store a new chat to database
     * @param User $receiver is user I want to send message to
     * @return JsonResponse response with success or failed message
     */
    public function store(User $receiver)
    {
        try {
            DB::beginTransaction();
            $createChatBetweenUsersRequest = Chat::CreateChatBetweenUserAndDriver($receiver, Auth::user());
            if($createChatBetweenUsersRequest) {
                return $createChatBetweenUsersRequest;
            }
            DB::commit();
            return api_response(message: 'تم إنشاء المحادثة بنجاح');
        } catch (Exception $e) {
            DB::rollBack();
            return api_response(message: 'هناك خطأ في إنشاء المحادثة', code: 500, errors: [$e->getMessage()]);
        }
    }

    /**
     * Remove chat from database
     * @param Chat $chat is chat i want to delete it
     * @return JsonResponse response with success or failed message
     */
    public function destroy(Chat $chat)
    {
        try {
            $chat->delete();
            return api_response(message: 'تم حذف المحادثة بنجاح');
        } catch (Exception $e) {
            return api_response(message: 'هناك خطأ في حذف المحادثة', code: 500, errors: [$e->getMessage()]);
        }
    }
}

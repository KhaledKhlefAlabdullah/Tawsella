<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactUsMessages\AnswereContactUsMessagesRequest;
use App\Http\Requests\ContactUsMessagesRequest;
use App\Mail\ContactUsMails;
use App\Models\ContactUsMessage;
use App\Models\User;
use App\Services\PaginationService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactUsMessageController extends Controller
{
    protected $paginationService;

    public function __construct(PaginationService $paginationService)
    {
        $this->paginationService = $paginationService;
    }

    /**
     * Display a listing of the contact-us messages.
     * @return JsonResponse with contact-us-messages, success message and status code 200 if success or with errors in failed
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-35
     */
    public function index(Request $request)
    {
        $query = ContactUsMessage::query()->where('is_answered', false);

        $contact_us = $this->paginationService->applyPagination($query, $request);

        return api_response(data: $contact_us->items(), message: 'Successfully getting contact us messages.', pagination: get_pagination($contact_us, $request));
    }

    /**
     * Store a newly created conatct-us message in storage.
     * @return JsonResponse with success message and status code 200 if success or with errors in failed
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-37
     */
    public function store(ContactUsMessagesRequest $request)
    {
        try {

            $validatedData = $request->validated();

            ContactUsMessage::create($validatedData);

            $admin = getAndCheckModelById(User::class, getAdminId());
            send_notifications($admin, 'The user: ' . $validatedData['sender_name'] . ' send new contact-us message');

            return api_response(message: 'contact us message send successfully');
        } catch (Exception $e) {
            return api_response(message: 'contact us message sending error', code: 500, errors: [$e->getMessage()]);
        }
    }

    /**
     * Answer the contact us message
     * @return JsonResponse with contact-us-messages, success message and status code 200 if success or with errors in failed
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-37
     * @param AnswereContactUsMessagesRequest $request
     * @param ContactUsMessage $contact_us
     * @return JsonResponse
     */
    public function answer(AnswereContactUsMessagesRequest $request, ContactUsMessage $contact_us)
    {
        try {

            $validatedData = $request->validated();

            $contact_us->update([
                'is_answered' => true
            ]);

            // this to send request by the email to the customer
            Mail::to($contact_us->email)->send(new ContactUsMails($validatedData['message']));

            return api_response(message: 'contact us message answered successfully');
        } catch (Exception $e) {
            return api_response(message: 'Error in answering contact us message.', code: 500, errors: [$e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     * @return JsonResponse with contact-us-messages, success message and status code 200 if success or with errors in failed
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-37
     */
    public function show(ContactUsMessage $contact_us)
    {
        return api_response(data: $contact_us, message: 'Successfully getting contact us message.');
    }

    /**
     * Remove the specified resource from storage.
     * @return JsonResponse with contact-us-messages, success message and status code 200 if success or with errors in failed
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-37
     */
    public function destroy(ContactUsMessage $contact_us)
    {
        try {
            $contact_us->delete();
            return api_response(message: 'Contact us message deleted successfully');
        } catch (Exception $e) {
            return api_response(message: 'Error in deleting contact us message.', code: 500, errors: [$e->getMessage()]);
        }
    }
}

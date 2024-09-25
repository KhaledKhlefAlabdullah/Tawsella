<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactUsMessages\AnswereContactUsMessagesRequest;
use App\Http\Requests\ContactUsMessagesRequest;
use App\Mail\ContactUsMails;
use App\Models\ContactUsMessage;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ContactUsMessageController extends Controller
{
    /**
     * Display a listing of the contact-us messages.
     * @return JsonResponse with contact-us-messages, success message and status code 200 if success or with errors in failed
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-35
     */
    public function index()
    {
        $contact_us = Auth::user()->contact_us_messages()->where('is_answered', false)->get();
        return api_response(data: $contact_us, message: 'Successfully getting contact us messages.');
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
            return api_response(errors: [$e->getMessage()], message: 'contact us message sending error', code: 500);
        }
    }

    /**
     * Answer the contact us message
     * @return JsonResponse with contact-us-messages, success message and status code 200 if success or with errors in failed
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-37
     */
    public function answer(AnswereContactUsMessagesRequest $request, ContactUsMessage $contactUsMessage)
    {
        try {

            $validatedData = $request->validated();

            $contactUsMessage->update([
                'is_answered' => true
            ]);

            // this to send request by the email to the cutomer
            Mail::to($contactUsMessage->email)->send(new ContactUsMails($validatedData['message']));

            return api_response(message: 'contact us message answered successfully');
        } catch (Exception $e) {
            return api_response(errors: [$e->getMessage()], message: 'Error in answering contact us message.', code: 500);
        }
    }

    /**
     * Display the specified resource.
     * @return JsonResponse with contact-us-messages, success message and status code 200 if success or with errors in failed
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-37
     */
    public function show(ContactUsMessage $contactUsMessage)
    {
        return api_response(data: $contactUsMessage);
    }

    /**
     * Remove the specified resource from storage.
     * @return JsonResponse with contact-us-messages, success message and status code 200 if success or with errors in failed
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-37
     */
    public function destroy(ContactUsMessage $contactUsMessage)
    {
        try {
            $contactUsMessage->delete();
            return api_response(message: 'Contact us message deleted successfully');
        } catch (Exception $e) {
            return api_response(errors: [$e->getMessage()],message: 'Error in deleting contact us message.', code: 500);
        }
    }
}

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
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-35
     * @return \Illuminate\Http\RedirectResponse with contact-us-messages, success message and status code 200 if success or with errors in failed
     */
    public function index()
    {
        try {

            $contact_us = Auth::user()->contact_us_messages()->where('is_answered', false)->get();

            return view('contctus', [$contact_us]);
        } catch (Exception $e) {
            return redirect()->back()->withErrors('Error in getting contact us messages.'."\n errors:".$e->getMessage())->withInput();
        }
    }

    /**
     * Store a newly created conatct-us message in storage.
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-37
     * @return JsonResponse with success message and status code 200 if success or with errors in failed
     */
    public function store(ContactUsMessagesRequest $request)
    {
        try {

            $validatedData = $request->validated();

            ContactUsMessage::create($validatedData);

            $admin = getAndCheckModelById(User::class, getAdminId());
            send_notifications($admin, 'The user: '.$validatedData['sender_name'].' send new contact-us message');

            return api_response(message: 'contact us message send successfuly');
        } catch (Exception $e) {
            return api_response(errors: [$e->getMessage()], message: 'contact us message sending error', code: 500);
        }
    }

    /**
     * Answer the contact us message
     * @return \Illuminate\Http\RedirectResponse with contact-us-messages, success message and status code 200 if success or with errors in failed
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
            Mail::to($contactUsMessage->email)->send(new ContactUsMails( $validatedData['message']));

            return view();
        } catch (Exception $e) {
            return redirect()->back()->withErrors('هنالك خطأ في جلب البيانات الرجاء المحاولة مرة أخرى.'."\n errors:".$e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     * @return \Illuminate\Http\RedirectResponse with contact-us-messages, success message and status code 200 if success or with errors in failed
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-37
     */
    public function show(ContactUsMessage $contactUsMessage)
    {
        try {

            return view('contact_us.show', ['contactDetails' => $contactUsMessage]);
        } catch (Exception $e) {
            return redirect()->back()->withErrors('Error in getting contact us message details.'."\n errors:".$e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     * @return \Illuminate\Http\RedirectResponse with contact-us-messages, success message and status code 200 if success or with errors in failed
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-37
     */
    public function destroy(ContactUsMessage $contactUsMessage)
    {
        try{

            $contactUsMessage->delete();

            return redirect()->back()->with('message', 'Contact us message deleted successfully');
        }
        catch(Exception $e){
            return redirect()->back()->withErrors('Error in deleting contact us message.'."\n errors:".$e->getMessage())->withInput();
        }
    }
}

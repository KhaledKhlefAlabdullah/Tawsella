<?php

namespace App\Http\Controllers;

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
     * @return JsonResponse with conatct-us-messages, success message and status code 200 if success or with errors in failed
     */
    public function index()
    {
        try {

            // get all unaswerd messages
            $contct_us = Auth::user()->contact_us_messages()->where('is_answerd', false)->get();

            return api_response(data: $contct_us, message: 'getting contact us messages successfully');
        } catch (Exception $e) {
            return api_response(errors: [$e->getMessage()], message: 'getting contact us messages error', code: 500);
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
     */
    public function answer(Request $request, ContactUsMessage $contactUsMessage)
    {
        try {

            $contactUsMessage->update([
                'is_answerd' => true
            ]);

            // this to send request by the email to the cutomer
            Mail::to($contactUsMessage->email)->send(new ContactUsMails($request->message));

            return view();
        } catch (Exception $e) {
            return redirect()->back()->withErrors('هنالك خطأ في جلب البيانات الرجاء المحاولة مرة أخرى.\nالاخطاء:' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ContactUsMessage $contactUsMessage)
    {
        try {

            $contactDetails = $contactUsMessage->select('admin_id', 'description', 'email', 'phone_number')->firstOrFail();

            return view('contact_us.show', ['contactDetails' => $contactDetails]);
        } catch (Exception $e) {
            return redirect()->back()->withErrors('هنالك خطأ في جلب البيانات الرجاء المحاولة مرة أخرى.\nالاخطاء:' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ContactUsMessage $contactUsMessage)
    {
        try {

            $contactUsMessage->delete();

            return redirect()->back();

        } catch (Exception $e) {
            return redirect()->back()->withErrors('هنالك خطأ في جلب البيانات الرجاء المحاولة مرة أخرى.\nالاخطاء:' . $e->getMessage())->withInput();
        }
    }
}

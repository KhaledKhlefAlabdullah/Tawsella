<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactUsMessagesRequest;
use App\Mail\ContactUsMails;
use App\Models\ContactUsMessage;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactUsMessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {

            $contct_us = ContactUsMessage::select('admin_id', 'description', 'email', 'phone_number')
                ->where('is_answerd', false)
                ->get();

            return view('contctus', [$contct_us]);
        } catch (Exception $e) {
            return redirect()->back()->withErrors('هنالك خطأ في جلب البيانات الرجاء المحاولة مرة أخرى.\nالاخطاء:' . $e->getMessage())->withInput();
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ContactUsMessagesRequest $request)
    {
        try {

            $validatedData = $request->validated();

            ContactUsMessage::create($validatedData);

            return api_response(message: 'أرسل رسالة اتصل بنا بنجاح');
        } catch (Exception $e) {
            return api_response(errors: $e->getMessage(), message: 'إرسال خطأ في رسالة اتصل بنا', code: 500);
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
        try{

            $contactUsMessage->delete();

            return redirect()->back();

        }
        catch(Exception $e){
            return redirect()->back()->withErrors('هنالك خطأ في جلب البيانات الرجاء المحاولة مرة أخرى.\nالاخطاء:' . $e->getMessage())->withInput();
        }
    }
}

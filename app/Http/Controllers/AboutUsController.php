<?php

namespace App\Http\Controllers;

use App\Http\Requests\AboutUsRequest;
use App\Models\AboutUs;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AboutUsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $aboutUsRecords = AboutUs::select('title', 'description', 'complaints_number')->first();

            if (request()->wantsJson()) {
                return api_response(data: $aboutUsRecords, message: 'نجحنا في الحصول على التفاصيل عنا');
            }

            $additional_info = AboutUs::where('is_general', false)->select('title', 'description')->get();

            return view('aboutus.index', ['aboutUsRecords' => $aboutUsRecords, 'additional_info' => $additional_info]);

        } catch (Exception $e) {
            if (request()->wantsJson()) {
                return api_response(errors: [$e->getMessage()], message: 'نجحنا في الحصول على التفاصيل عنا', code: 500);
            }

            return redirect()->back()->withErrors('هنالك خطأ في جلب البيانات الرجاء المحاولة مرة أخرى.\nالاخطاء:' . $e->getMessage())->withInput();
        }
    }


    /**
     * Get the additional information
     */
    public function get_addition_information()
    {
        try {

            $data = AboutUs::where('is_general', false)->select('title', 'description')->get();

            return api_response(data: $data, message: 'الحصول على معلومات إضافية ناجحة');
        } catch (Exception $e) {
            return api_response(errors: [$e->getMessage()], message: 'الحصول على خطأ معلومات إضافية', code: 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // يمكنك تعيين البيانات التي تحتاجها في صفحة الإنشاء هنا
        return view('aboutus.create');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AboutUs $aboutUs)
    {
        // يمكنك تعيين البيانات التي تحتاجها في صفحة التحرير هنا
        return view('aboutus.edit', ['aboutUs' => $aboutUs]);
    }

    /**
     * Store a newly created resource in storage.
     * Or
     * Update the exists aboutus details
     */
    public function store_or_update(AboutUsRequest $request)
    {
        try {

            $validatedData = $request->validated();

            if (AboutUs::where('is_general', true)->count() == 0) {
                AboutUs::create($validatedData);
            } else {
                AboutUs::where('is_general', true)->first()->update($validatedData);
            }

            return redirect()->route('aboutus.index')->with('success', 'تم إنشاء نبذة عنا بنجاح.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors('هنالك خطأ في جلب البيانات الرجاء المحاولة مرة أخرى.\nالاخطاء:' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create_additional_info()
    {
        // يمكنك تعيين البيانات التي تحتاجها في صفحة الإنشاء هنا
        return view('aboutus.create_additional_info');
    }

    /**
     * Create Additional info records
     */
    public function store_additional_info(AboutUsRequest $request){
        try{

            $validatedData = $request->validated();

            AboutUs::create($validatedData);

            return redirect()->route('aboutus.index')->with('success', 'تم إضافة معلومات إضافية بنجاح.');

        }
        catch(Exception $e){
            return redirect()->back()->withErrors('هنالك خطأ في جلب البيانات الرجاء المحاولة مرة أخرى.\nالاخطاء:' . $e->getMessage())->withInput();
        }
    }


    public function edit_additional_info(AboutUs $aboutUs)
    {
        // يمكنك تعيين البيانات التي تحتاجها في صفحة التحرير هنا
        return view('aboutus.edit', ['aboutUs' => $aboutUs]);
    }

     /**
     * Update Additional info records
     */
    public function update_additional_info(AboutUsRequest $request, AboutUs $aboutUs){
        try{

            $validatedData = $request->validated();

            $aboutUs->update($validatedData);

            return redirect()->route('aboutus.index')->with('success', 'تم تعديل البيانات بنجاح.');

        }
        catch(Exception $e){
            return redirect()->back()->withErrors('هنالك خطأ في جلب البيانات الرجاء المحاولة مرة أخرى.\nالاخطاء:' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AboutUs $aboutUs)
    {
        try {
            $aboutUs->delete();
            return redirect()->route('aboutus.index')->with('success', 'تم حذف نبذة عنا بنجاح.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors('هنالك خطأ في جلب البيانات الرجاء المحاولة مرة أخرى.\nالاخطاء:' . $e->getMessage())->withInput();
        }
    }
}

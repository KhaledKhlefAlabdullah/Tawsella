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
            $aboutUsRecords = AboutUs::select('title','description','complaints_number')->first();

            if (request()->wantsJson()) {
                return api_response(data: $aboutUsRecords, message: 'successfully getting about us details');
            }

            return view('aboutus.index', ['aboutUsRecords' => $aboutUsRecords]);
        } catch (Exception $e) {
            if (request()->wantsJson()) {
                return api_response(errors: [$e->getMessage()], message: 'successfully getting about us details', code: 500);
            }

            return abort('there error in getting the data', 500);
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
     * Store a newly created resource in storage.
     * Or
     * Update the exists aboutus details
     */
    public function store_or_update(AboutUsRequest $request)
    {
        try {

            $validatedData = $request->validated();

            if (AboutUs::count() == 0) {
                AboutUs::create($validatedData);
            } else {
                AboutUs::first()->update($validatedData);
            }

            return redirect()->route('aboutus.index')->with('success', 'تم إنشاء نبذة عنا بنجاح.');
        } catch (Exception $e) {
            return abort('there error in getting the data', 500);
        }
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
     * Remove the specified resource from storage.
     */
    public function destroy(AboutUs $aboutUs)
    {
        try {
            $aboutUs->delete();
            return redirect()->route('aboutus.index')->with('success', 'تم حذف نبذة عنا بنجاح.');
        } catch (Exception $e) {
            return abort('there error in deleting aboutus data', 500);
        }
    }
}

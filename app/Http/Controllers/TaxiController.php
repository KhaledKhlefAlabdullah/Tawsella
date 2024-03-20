<?php

namespace App\Http\Controllers;

use App\Models\Taxi;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TaxiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // احصل على جميع السجلات وعرضها، يمكنك تخصيص هذه الوظيفة حسب احتياجاتك
        $taxis = Taxi::all();
        return view('taxis.index', compact('taxis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try{
            $drivers = User::where('user_type', 'driver')
            ->join('user_profiles', 'users.id', '=', 'user_profiles.user_id')
            ->select('users.id', 'user_profiles.name')->get();

        // عرض الاستمارة لإنشاء سجل جديد
        return view('taxis.create', ['drivers' => $drivers]);
        }
        catch(Exception $e){
            abort('there error in redirect to the add taxi form',500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // التحقق من البيانات المدخلة
        $validatedData = $request->validate([
            'driver_id' => 'required|exists:users,id',
            'car_name' => 'required|string',
            'lamp_number' => 'required|string',
            'plate_number' => 'required|string|unique:taxis,plate_number',
            'car_detailes' => 'nullable|string',
        ]);

        // التأكد من وجود قيمة لـ car_detailes قبل تخزينها
        $carDetails = $validatedData['car_detailes'] ?? ''; // قيمة افتراضية فارغة إذا لم يتم تقديم قيمة
        $validatedData['car_detailes'] = $carDetails;

        // إنشاء سجل جديد
        Taxi::create($validatedData);

        // إعادة توجيه أو عرض رسالة نجاح
        return redirect()->route('taxis.index')->with('success', 'تم إنشاء سجل التاكسي بنجاح.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Taxi  $taxi
     * @return \Illuminate\Http\Response
     */
    public function show(Taxi $taxi)
    {
        // عرض تفاصيل السجل المحدد
        return view('taxis.show', compact('taxi'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Taxi  $taxi
     * @return \Illuminate\Http\Response
     */
    public function edit(Taxi $taxi)
    {
        // عرض الاستمارة لتحرير السجل
        return view('taxis.edit', compact('taxi'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Taxi  $taxi
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Taxi $taxi)
    {
        // التحقق من البيانات المدخلة
        $validatedData = $request->validate([
            'driver_id' => 'required|exists:users,id',
            'care_name' => 'required|string',
            'lamp_number' => 'required|string',
            'plate_number' => [
                'required',
                'string',
                Rule::unique('taxis')->ignore($taxi->id),
            ],
            'car_details' => 'nullable|string',
        ]);

        // تحديث السجل بالبيانات المحدثة
        $taxi->update($validatedData);

        // إعادة توجيه أو عرض رسالة نجاح
        return redirect()->route('taxis.index')->with('success', 'تم تحديث سجل التاكسي بنجاح.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Taxi  $taxi
     * @return \Illuminate\Http\Response
     */
    public function destroy(Taxi $taxi)
    {
        // حذف السجل المحدد
        $taxi->delete();

        // إعادة توجيه أو عرض رسالة نجاح
        return redirect()->route('taxis.index')->with('success', 'تم حذف سجل التاكسي بنجاح.');
    }
}

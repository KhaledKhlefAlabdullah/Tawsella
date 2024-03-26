<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaxiRequest;
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
        try {
            
            // get the drivers dont have taxi
            $drivers = User::where([
                'users.user_type' => 'driver',
                'users.is_active' => true
            ])
            ->leftJoin('taxis', 'users.id', '=', 'taxis.driver_id')
            ->whereNull('taxis.id')
            ->join('user_profiles', 'users.id', '=', 'user_profiles.user_id')
            ->select('users.id', 'user_profiles.name','user_profiles.avatar')
            ->get();
            
            return view('taxis.create', compact('drivers'));

        } catch (Exception $e) {
            abort(500, 'there error in redirect to the add taxi form');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaxiRequest $request)
    {
        try {
            // التحقق من البيانات المدخلة
            $validatedData = $request->validated();
            
            // إنشاء سجل جديد
            Taxi::create($validatedData);

            // إعادة توجيه أو عرض رسالة نجاح
            return redirect()->back()->with('success', 'تم إنشاء سجل التاكسي بنجاح.');
        } catch (Exception $e) {
            abort(500, 'there error in creatting taxi'.$e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Taxi  $taxi
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
     */
    public function edit(Taxi $taxi)
    {
        // عرض الاستمارة لتحرير السجل
        return view('taxis.edit', compact('taxi'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Models\Taxi  $taxi
     */
    public function update(TaxiRequest $request, Taxi $taxi)
    {
        try {
            // التحقق من البيانات المدخلة
            $validatedData = $request->validated();

            // تحديث السجل بالبيانات المحدثة
            $taxi->update($validatedData);

            // إعادة توجيه أو عرض رسالة نجاح
            return redirect()->route('taxis.index')->with('success', 'تم تحديث سجل التاكسي بنجاح.');
        } catch (Exception $e) {
            abort(500, 'there error in updatting taxi details');
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param  \App\Models\Taxi  $taxi
     */
    public function destroy(Taxi $taxi)
    {
        // حذف السجل المحدد
        $taxi->delete();

        // إعادة توجيه أو عرض رسالة نجاح
        return redirect()->route('taxis.index')->with('success', 'تم حذف سجل التاكسي بنجاح.');
    }
}

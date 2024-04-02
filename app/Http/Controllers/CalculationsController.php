<?php

namespace App\Http\Controllers;

use App\Models\Calculations;
use Exception;
use Illuminate\Http\Request;

class CalculationsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $calculations = Calculations::all();
            return view('calculations.index', ['calculations' => $calculations]);
        } catch (Exception $e) {
            return redirect()->back()->withErrors('هنالك خطأ في جلب البيانات الرجاء المحاولة مرة أخرى.\nالاخطاء:' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('calculations.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'driver_id' => 'required',
                'taxi_movement_id' => 'required',
                'calculate' => 'required|numeric',
            ]);

            Calculations::create($data);

            return redirect()->route('calculations.index')->with('success', 'تم إنشاء الحساب بنجاح');
        } catch (Exception $e) {
            return redirect()->back()->withErrors('هنالك خطأ في جلب البيانات الرجاء المحاولة مرة أخرى.\nالاخطاء:' . $e->getMessage())->withInput();
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Calculations $calculations)
    {
        return view('calculations.show', ['calculations' => $calculations]);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Calculations $calculations)
    {
        return view('calculations.edit', ['calculations' => $calculations]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Calculations $calculations)
    {
        try {
            $data = $request->validate([
                'driver_id' => 'required',
                'taxi_movement_id' => 'required',
                'calculate' => 'required|numeric',
            ]);

            $calculations->update($data);

            return redirect()->route('calculations.index')->with('success', 'تم تحديث الحساب بنجاح');
        } catch (Exception $e) {
            return redirect()->back()->withErrors('هنالك خطأ في جلب البيانات الرجاء المحاولة مرة أخرى.\nالاخطاء:' . $e->getMessage())->withInput();
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Calculations $calculations)
    {
        try {
            $calculations->delete();

            return redirect()->route('calculations.index')->with('success', 'تم حذف سجل الحساب بنجاح');
        } catch (Exception $e) {
            return redirect()->back()->withErrors('هنالك خطأ في جلب البيانات الرجاء المحاولة مرة أخرى.\nالاخطاء:' . $e->getMessage())->withInput();
        }
    }
}

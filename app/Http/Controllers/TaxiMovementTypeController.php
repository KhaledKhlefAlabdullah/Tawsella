<?php

namespace App\Http\Controllers;

use App\Models\TaxiMovementType;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TaxiMovementTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $movementTypes = TaxiMovementType::select('id', 'type', 'price', 'description', 'is_onKM')->whereNotIn('id', ['t-m-t-1', 't-m-t-2'])->get();

            if (request()->wantsJson())
                return api_response(data: $movementTypes, message: 'getting-movement-type-error');

            return view('servises', ['movementTypes' => $movementTypes]);
        } catch (Exception $e) {
            if (request()->wantsJson())
                return api_response(errors: [$e->getMessage()], message: 'getting-movement-type-success', code: 500);
            return redirect()->back()->withErrors('هنالك خطأ في جلب البيانات الرجاء المحاولة مرة أخرى.\nالاخطاء:' . $e->getMessage())->withInput();
        }
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('taxi_movement_types.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    try {
        // تحقق من صحة البيانات المدخلة باستخدام القواعد المحددة في نموذج البيانات
        $validatedData = $request->validate([
            'type' => ['required'],
            'price' => ['required', 'numeric'],
            'description' => ['required'],
            'is_onKM' => ['required', 'boolean'],
        ]);

        // إنشاء نوع حركة تاكسي جديد وحفظه في قاعدة البيانات
        TaxiMovementType::create($validatedData);

        // إعادة توجيه المستخدم برسالة نجاح
        return redirect()->route('servises')->with('success', 'تم إنشاء نوع الحركة بنجاح.');
    } catch (ValidationException $e) {
        // إذا حدث خطأ في التحقق من الصحة، يُعاد توجيه المستخدم مع رسالة الخطأ والبيانات المدخلة
        return redirect()->back()->withErrors($e->errors())->withInput();
    } catch (Exception $e) {
        // إذا حدث خطأ آخر، يُعاد توجيه المستخدم مع رسالة الخطأ والبيانات المدخلة
        return redirect()->back()->withErrors('هنالك خطأ في جلب البيانات الرجاء المحاولة مرة أخرى. الخطأ: ' . $e->getMessage())->withInput();
    }
}


    /**
     * Display the specified resource.
     */
    public function show(TaxiMovementType $taxiMovementType)
    {
        return view('taxi_movement_types.show', ['movementType' => $taxiMovementType]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TaxiMovementType $taxiMovementType)
    {
        return view('taxi_movement_types.edit', ['movementType' => $taxiMovementType]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TaxiMovementType $taxiMovementType)
    {
        try {
            $data = $request->validate([
                'type' => 'required',
                'price' => 'required|numeric',
            ]);

            $taxiMovementType->update($data);

            return redirect()->route('taxi_movement_types.index')->with('success', 'تم تحديث نوع الحركة بنجاح.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors('هنالك خطأ في جلب البيانات الرجاء المحاولة مرة أخرى.\nالاخطاء:' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TaxiMovementType $movementType)
{
    try {
        // حذف الحركة المحددة
        $movementType->delete();

        // إعادة التوجيه مع رسالة نجاح
        return redirect()->route('taxi_movement_types.index')->with('success', 'تم حذف الحركة بنجاح.');
    } catch (Exception $e) {
        // إذا حدث خطأ، يتم التعامل معه وإعادة التوجيه مع رسالة الخطأ
        return redirect()->back()->withErrors('حدث خطأ أثناء محاولة حذف الحركة. الخطأ: ' . $e->getMessage());
    }
}

}

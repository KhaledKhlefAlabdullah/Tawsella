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
            $movements = TaxiMovementType::select('id', 'type', 'price', 'payment', 'description')->whereNotIn('id', ['t-m-t-1', 't-m-t-2', 't-m-t-3'])->get();

            $movementTypes = TaxiMovementType::select('id', 'type', 'price', 'payment', 'description', 'is_onKM')->whereIn('id', ['t-m-t-1', 't-m-t-2', 't-m-t-3'])->get();
            if (request()->wantsJson())
                return api_response(data: $movements, message: ' نجح الحصول على انواع الطلبات');

            return view('services', ['movementTypes' => $movementTypes, 'movements' => $movements]);
        } catch (Exception $e) {
            if (request()->wantsJson())
                return api_response(errors: [$e->getMessage()], message: 'حدث خطأ في الحصول على انواع الطلبات', code: 500);
            return redirect()->back()->withErrors('هنالك خطأ في جلب البيانات الرجاء المحاولة مرة أخرى.\nالاخطاء:' . $e->getMessage())->withInput();
        }
    }


    public function getMovement3()
    {
        try {
            $movement = getAndCheckModelById(TaxiMovementType::class, 't-m-t-3');

            $data = [
                'id' => $movement->id,
                'type' => $movement->type,
                'price' => $movement->price,
                'payment' => $movement->payment,
                'description' => $movement->description
            ];
            return api_response(data: $data, message: ' نجح الحصول على  البيانات');
        } catch (Exception $e) {
            return api_response(errors: [$e->getMessage()], message: 'حدث خطأ في الحصول على انواع الطلبات', code: 500);
        }
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('service.create');
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
                'description' => ['nullable'],
                'is_onKM' => ['required', 'boolean'],
                'payment' => ['required', 'in:$,TL']

            ]);

            // إنشاء نوع حركة تاكسي جديد وحفظه في قاعدة البيانات
            TaxiMovementType::create($validatedData);

            // إعادة توجيه المستخدم برسالة نجاح
            return redirect()->route('services')->with('success', 'تم إنشاء نوع الحركة بنجاح.');
        } catch (ValidationException $e) {
            // إذا حدث خطأ في التحقق من الصحة، يُعاد توجيه المستخدم مع رسالة الخطأ والبيانات المدخلة
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (Exception $e) {
            // إذا حدث خطأ آخر، يُعاد توجيه المستخدم مع رسالة الخطأ والبيانات المدخلة
            return redirect()->back()->withErrors('هنالك خطأ في جلب البيانات الرجاء المحاولة مرة أخرى. الخطأ: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TaxiMovementType $movementType)
    {
        return view('service.edit', ['movementType' => $movementType]);
    }

    public function update(Request $request, TaxiMovementType $movementType)
    {
        try {

            $data = $request->validate([
                'type' => 'required',
                'price' => 'required|numeric',
                'description' => 'nullable|string',
                'is_onKM' => 'required|boolean',
                'payment' => 'required|in:$,TL'
            ]);
            $movementType->update($data);

            return redirect()->route('services')->with('success', 'تم تحديث نوع الحركة بنجاح.');
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
            return redirect()->route('services')->with('success', 'تم حذف الحركة بنجاح.');
        } catch (Exception $e) {
            // إذا حدث خطأ، يتم التعامل معه وإعادة التوجيه مع رسالة الخطأ
            return redirect()->back()->withErrors('حدث خطأ أثناء محاولة حذف الحركة. الخطأ: ' . $e->getMessage());
        }
    }
}

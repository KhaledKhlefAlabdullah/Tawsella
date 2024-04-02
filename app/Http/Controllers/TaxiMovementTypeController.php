<?php

namespace App\Http\Controllers;

use App\Models\TaxiMovementType;
use Exception;
use Illuminate\Http\Request;

class TaxiMovementTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $movementTypes = TaxiMovementType::select('id', 'type', 'price','description','is_onKM')->whereNotIn('id',['t-m-t-1','t-m-t-2'])->get();

            if (request()->wantsJson())
                return api_response(data: $movementTypes, message: 'getting-movement-type-error');

            return view('servises', ['movementTypes' => $movementTypes]);
        } catch (Exception $e) {
            if (request()->wantsJson())
                return api_response(errors: [$e->getMessage()], message: 'getting-movement-type-success', code: 500);
            return redirect()->back()->withErrors('هنالك خطأ في جلب البياانت الرجاء المحاولة مؤة أخرى.\nالاخطاء:' . $e->getMessage())->withInput();
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
        $data = $request->validated();

        TaxiMovementType::create($data);

        return redirect()->route('taxi_movement_types.index')->with('success', 'تم إنشاء نوع الحركة بنجاح.');
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
        $data = $request->validate([
            'type' => 'required',
            'price' => 'required|numeric',
        ]);

        $taxiMovementType->update($data);

        return redirect()->route('taxi_movement_types.index')->with('success', 'تم تحديث نوع الحركة بنجاح.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TaxiMovementType $taxiMovementType)
    {
        $taxiMovementType->delete();

        return redirect()->route('taxi_movement_types.index')->with('success', 'تم حذف نوع الحركة بنجاح.');
    }
}

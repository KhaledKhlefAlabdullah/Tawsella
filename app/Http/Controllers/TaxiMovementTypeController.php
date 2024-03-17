<?php

namespace App\Http\Controllers;

use App\Models\TaxiMovementType;
use Illuminate\Http\Request;

class TaxiMovementTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $movementTypes = TaxiMovementType::all();
        return view('taxi_movement_types.index', ['movementTypes' => $movementTypes]);
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
        $data = $request->validate([
            'type' => 'required',
            'price' => 'required|numeric',
        ]);

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

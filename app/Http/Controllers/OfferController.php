<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use App\Models\TaxiMovementType;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OfferController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {

            $offers = Offer::select(
                'offers.offer',
                'offers.value_of_discount',
                'offers.valide_date',
                'taxi_movement_types.type',
                'taxi_movement_types.price'
            )
                ->join('taxi_movement_types', 'offers.movement_type_id', '=', 'taxi_movement_types.id')
                ->where('offers.valide_date', '>=', now())
                ->get();

            if (request()->wantsJson())
                return api_response(data: $offers, message: 'getting offers success');

            return view('offers.index', ['offers' => $offers]);
            
        } catch (Exception $e) {
            if (request()->wantsJson())
                return api_response(errors: $e->getMessage(), message: 'getting offers error', code: 500);
            return abort('there error in getting offers', 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $movementTypes = TaxiMovementType::all();
        $admins = User::where('user_type', 'admin')->get();

        return view('offers.create', ['movementTypes' => $movementTypes, 'admins' => $admins]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'movement_type_id' => 'required',
            'offer' => 'required',
            'value_of_discount' => 'required|numeric',
            'valide_date' => 'required|date',
        ]);

        // تعيين admin_id بمعرف المستخدم الحالي
        $data['admin_id'] = Auth::id();

        Offer::create($data);

        return redirect()->route('offers.index')->with('success', 'تم إنشاء العرض بنجاح.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Offer $offer)
    {
        return view('offers.show', ['offer' => $offer]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Offer $offer)
    {
        $movementTypes = TaxiMovementType::all();
        $admins = User::where('user_type', 'admin')->get();

        return view('offers.edit', ['offer' => $offer, 'movementTypes' => $movementTypes, 'admins' => $admins]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Offer $offer)
    {
        $data = $request->validate([
            'movement_type_id' => 'required',
            'admin_id' => 'required',
            'offer' => 'required',
            'value_of_discount' => 'required|numeric',
            'valide_date' => 'required|date',
        ]);

        $offer->update($data);

        return redirect()->route('offers.index')->with('success', 'تم تحديث العرض بنجاح.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Offer $offer)
    {
        $offer->delete();

        return redirect()->route('offers.index')->with('success', 'تم حذف العرض بنجاح.');
    }
}

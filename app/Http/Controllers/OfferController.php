<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use App\Models\TaxiMovementType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class OfferController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $offers = Offer::all();
        return view('offers.index', ['offers' => $offers]);
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


    public function index()
    {
        try{

            return api_response(message:'get-aboutus-success');
        }
        catch(Exception $e){
            return api_response(message:'get-aboutus-success');
        }
    }


}

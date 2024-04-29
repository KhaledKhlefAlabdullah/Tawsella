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
                'offers.id',
                'offers.offer',
                'offers.value_of_discount',
                'offers.valide_date',
                'taxi_movement_types.type',
                'taxi_movement_types.price',
                'taxi_movement_types.description'

            )
                ->join('taxi_movement_types', 'offers.movement_type_id', '=', 'taxi_movement_types.id')
                ->where('offers.valide_date', '>=', now())
                ->get();

            if (request()->wantsJson())
                return api_response(data: $offers, message: 'تم الحصول على العروض بنجاح');

            return view('offers.index', ['offers' => $offers]);
        } catch (Exception $e) {
            if (request()->wantsJson())
                return api_response(errors: $e->getMessage(), message: 'فشل الحصول على العروض', code: 500);
            return redirect()->back()->withErrors('هنالك خطأ في جلب البيانات الرجاء المحاولة مرة أخرى.\nالاخطاء:' . $e->getMessage())->withInput();
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
        try {
            $data = $request->validate([
                'movement_type_id' => 'required',
                'offer' => 'required',
                'value_of_discount' => 'required|numeric',
                'valide_date' => 'required|date',
                'description' => 'sometimes|string|nullable'
            ]);

            // تعيين admin_id بمعرف المستخدم الحالي
            $data['admin_id'] = Auth::id();

            Offer::create($data);

            return redirect()->route('offers.index')->with('success', 'تم إنشاء العرض بنجاح.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors('هنالك خطأ في جلب البيانات الرجاء المحاولة مرة أخرى.\nالاخطاء:' . $e->getMessage())->withInput();
        }
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
        try {
            $data = $request->validate([
                'movement_type_id' => 'required',
                'admin_id' => 'required',
                'offer' => 'required',
                'value_of_discount' => 'required|numeric',
                'valide_date' => 'required|date',
            ]);

            $offer->update($data);

            return redirect()->route('offers.index')->with('success', 'تم تحديث العرض بنجاح.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors('هنالك خطأ في جلب البيانات الرجاء المحاولة مرة أخرى.\nالاخطاء:' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Offer $offer)
    {
        try {
            $offer->delete();

            return redirect()->route('offers.index')->with('success', 'تم حذف العرض بنجاح.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors('هنالك خطأ في جلب البيانات الرجاء المحاولة مرة أخرى.\nالاخطاء:' . $e->getMessage())->withInput();
        }
    }
}

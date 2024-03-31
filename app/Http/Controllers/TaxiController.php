<?php

namespace App\Http\Controllers;

use App\Events\GetTaxiLocationsEvent;
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
        try{

            // احصل على جميع السجلات وعرضها، يمكنك تخصيص هذه الوظيفة حسب احتياجاتك
            $taxis = Taxi::select('user_profiles.name as driverName','taxis.id','taxis.car_name','taxis.lamp_number','taxis.plate_number')
            ->join('user_profiles','taxis.driver_id','=','user_profiles.user_id')
            ->get();
            return view('taxis.index', compact('taxis'));
        }
        catch(Exception $e){
            return back()->with('error','هناك خطأ في جلب البيانات');
        }
        
    }

    /**
     * Get the taxi location 
     */
    public function getTaxiLocation(Request $request, string $driver_id){
        try{

            $request->validate([
                'lat' => 'nomeric|required',
                'log' => 'nomeric|required'
            ]);

            $taxi_id = Taxi::where('driver_id',$driver_id)->first()->id;

            GetTaxiLocationsEvent::dispatch(
                $taxi_id,
                $request->lat,
                $request->long
            );
            return api_response(message:'location getting success');
        }
        catch(Exception $e){
            return api_response(errors:$e->getMessage(),message:'there error in gettign taxi location',code:500);
        }
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
            return redirect()->back()->withErrors(['error' => $e->getMessage().'An error occurred. Please try again.'])->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Taxi  $taxi
     */
    public function edit(Taxi $taxi)
    {
        try{

            $drivers = User::select('users.id','user_profiles.name','user_profiles.avatar')
            ->join('user_profiles','users.id','=','user_profiles.user_id')->where('users.user_type','driver')->get();

            return view('taxis.edit', compact('taxi','drivers'));

        }
        catch(Exception $e){
            return redirect()->back()->withErrors(['error' => $e->getMessage().'An error occurred. Please try again.'])->withInput();
        }
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
            return redirect()->back()->withErrors(['error' => $e->getMessage().'An error occurred. Please try again.'])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param  \App\Models\Taxi  $taxi
     */
    public function destroy(string $id)
    {
        try{

        $taxi = getAndCheckModelById(Taxi::class,$id);
        // حذف السجل المحدد

        $taxi->delete();

        // إعادة توجيه أو عرض رسالة نجاح
        return redirect()->route('taxis.index')->with('success', 'تم حذف سجل التاكسي بنجاح.');
        }
        catch(Exception $e){
            return redirect()->back()->withErrors(['error' => $e->getMessage().'An error occurred. Please try again.'])->withInput();
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Calculations;
use App\Models\TaxiMovement;
use App\Models\User;
use App\Models\UserProfile;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class CalculationsController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {

            $drivers = User::select('users.id', 'up.name', 't.plate_number')
                ->join('user_profiles as up', 'users.id', '=', 'up.user_id')
                ->join('taxis as t', 'users.id', '=', 't.driver_id')->get();
            $combinedAccounts = [];
            foreach ($drivers as $driver) {
                $driver_id = $driver->id;
                $total_today = $this->todayAccounts($driver_id);
                $total_previous = $this->totalAccounts($driver_id);

                $combinedAccounts[] = (object)[
                    'driver_id' => $driver_id,
                    'name' => $driver->name,
                    'plate_number' => $driver->plate_number,
                    'today_account' => $total_today,
                    'all_account' => $total_previous
                ];
            }


            return view('calculations.index', ['calculations' => $combinedAccounts]);
        } catch (Exception $e) {
            return redirect()->back()->withErrors('هنالك خطأ في جلب البيانات الرجاء المحاولة مرة أخرى.\nالاخطاء:' . $e->getMessage())->withInput();
        }
    }


    /**
     * Calculate today accounts
     */
    public function todayAccounts(string $driver_id)
    {
        try {
            // Get today's date
            $today = Carbon::now()->toDateString();

            $todayAccounts = Calculations::where('driver_id', $driver_id)
                ->where('is_bring', false)
                ->whereDate('created_at', $today)
                ->sum('totalPrice');

            return $todayAccounts ?? 0;
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage() . 'هناك خطأ في حساب المبالغ التي استلمها السائق اليوم')->withInput();
        }
    }

    /**
     * Get the all previos accounts for driver
     */
    public function totalAccounts(string $driver_id)
    {
        try {
            $totalAccounts = Calculations::where('driver_id', $driver_id)
                ->where('is_bring', false)
                ->sum('totalPrice');

            return $totalAccounts ?? 0;
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage() . 'هناك خطأ في حساب المبالغ التي استلمها السائق')->withInput();
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
    // public function show(string $driver_id)
    // {
    //     try {

    //         $driverMovements = TaxiMovement::where(['driver_id' => $driver_id, 'is_completed' => true])->count();
    //         $totalMount = $this->totalAccounts($driver_id);
    //         $totalWay = Calculations::where('driver_id', $driver_id)->sum('way');
    //         $details = [
    //             'driverMovements' => $driverMovements,
    //             'totalMount' => $totalMount,
    //             'totalWay' => $totalWay
    //         ];

    //         $movements = TaxiMovement::select(
    //             'taxi_movements.my_address as saddress',
    //             'taxi_movements.destnation_address as eaddress',
    //             'taxi_movements.start_latitude as slat',
    //             'taxi_movements.start_longitude as along',
    //             'taxi_movements.end_latitude as elat',
    //             'taxi_movements.end_longitude as elong',
    //             'taxi_movements.created_at as date',
    //             'c.totalPrice',
    //             'c.way'
    //         )
    //             ->join('calculations as c', 'taxi_movements.id', '=', 'c.taxi_movement_id')
    //             ->where(['taxi_movements.driver_id' => $driver_id, 'taxi_movements.is_completed' => true])
    //             ->get();

    //         return view('calculations.show', ['details' => $details, 'movements' => $movements]);
    //     } catch (Exception $e) {
    //         return redirect()->back()->withErrors('هنالك خطأ في جلب البيانات الرجاء المحاولة مرة أخرى.\nالاخطاء:' . $e->getMessage())->withInput();
    //     }
    // }

    public function show(string $driver_id)
    {
        try {
            $driverMovements = TaxiMovement::where(['driver_id' => $driver_id, 'is_completed' => true])->count();
            $totalMount = $this->totalAccounts($driver_id);
            $totalWay = Calculations::where('driver_id', $driver_id)->sum('way');
            $details = [
                'driverMovements' => $driverMovements,
                'totalMount' => $totalMount,
                'totalWay' => $totalWay
            ];

            $movements = TaxiMovement::select(
                'taxi_movements.my_address as saddress',
                'taxi_movements.destnation_address as eaddress',
                'taxi_movements.start_latitude as slat',
                'taxi_movements.start_longitude as along',
                'taxi_movements.end_latitude as elat',
                'taxi_movements.end_longitude as elong',
                'taxi_movements.created_at as date',
                'c.totalPrice',
                'c.way'
            )
                ->join('calculations as c', 'taxi_movements.id', '=', 'c.taxi_movement_id')
                ->where([
                    'taxi_movements.driver_id' => $driver_id,
                    'taxi_movements.is_completed' => true
                ])
                ->whereHas('calculations', function ($query) {
                    $query->where('is_bring', false);
                })
                ->get();

            return view('calculations.show', ['details' => $details, 'movements' => $movements]);
        } catch (Exception $e) {
            return redirect()->back()->withErrors('هنالك خطأ في جلب البيانات الرجاء المحاولة مرة أخرى.\nالاخطاء:' . $e->getMessage())->withInput();
        }
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
     * Bring The mounts
     */
    public function bring(string $id)
    {
        try {
            
            $bringCout = Calculations::where(['driver_id' => $id, 'is_bring' => false])->count();
            if($bringCout == 0){
                return redirect()->route('drivers.index')->with('success', 'السائق ليس لديه أي ملغ بعد');
            }
            Calculations::where(['driver_id' => $id, 'is_bring' => false])
                ->update(['is_bring' => true]);

            return redirect()->route('drivers.index')->with('success', 'تم إستلام المبلغ بنجاح');
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

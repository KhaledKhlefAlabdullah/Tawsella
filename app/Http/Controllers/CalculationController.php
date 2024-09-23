<?php

namespace App\Http\Controllers;

use App\Enums\UserEnums\UserType;
use App\Http\Requests\CalculationRequest;
use App\Models\Calculation;
use App\Models\TaxiMovement;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class CalculationController extends Controller
{

    /**
     * View list of calculations.
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-
     * @return \Illuminate\Http\RedirectResponse to view page
     */
    public function index()
    {
        try {
            $drivers = User::where('user_type', UserType::TaxiDriver)->paginate(10);

            return view('calculations.index', [
                'calculations' => Calculation::mappingDriversCalculations($drivers),
                'drivers' => $drivers
            ]);
        } catch (Exception $e) {
            return redirect()->back()->withErrors('Error in getting drivers with calculations'."\n errors:" . $e->getMessage())->withInput();
        }
    }


    /**
     * Show the form for creating a new resource.
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-
     * @return \Illuminate\Http\RedirectResponse to view page
     */
    public function create()
    {
        return view('calculations.create');
    }

    /**
     * Store a newly created resource in storage.
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-
     * @return \Illuminate\Http\RedirectResponse to view page
     */
    public function store(CalculationRequest $request)
    {
        try {
            $validatedData = $request->validated();

            Calculation::create($validatedData);

            return redirect()->route('calculations.index')->with('success', 'تم إنشاء الحساب بنجاح');
        } catch (Exception $e) {
            return redirect()->back()->withErrors('هنالك خطأ في جلب البيانات الرجاء المحاولة مرة أخرى.'."\n errors:".$e->getMessage())->withInput();
        }
    }


    /**
     * Display the specified resource.
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-
     * @return \Illuminate\Http\RedirectResponse to view page
     */
    public function show(User $driver)
    {
        try {
            $driverMovements = count_items(TaxiMovement::class,['driver_id' => $driver->id, 'is_completed' => true]);
            $totalMount = Calculation::totalAccounts($driver->id);
            $totaldistance = Calculation::where('driver_id', $driver->id)->sum('distance');
            $movements = Calculation::driverMovementsCalculations($driver->id);
            $details = [
                'driverMovements' => $driverMovements,
                'totalMount' => $totalMount,
                'totaldistance' => $totaldistance
            ];

            return view('calculations.show', ['details' => $details, 'movements' => $movements]);
        } catch (Exception $e) {
            return redirect()->back()->withErrors('هنالك خطأ في جلب البيانات الرجاء المحاولة مرة أخرى.'."\n errors:".$e->getMessage())->withInput();
        }
    }



    /**
     * Show the form for editing the specified resource.
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application to view page
     */
    public function edit(Calculation $calculations)
    {
        return view('calculations.edit', ['calculations' => $calculations]);
    }


    /**
     * Update the specified resource in storage.
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-
     * @return \Illuminate\Http\RedirectResponse to view page
     */
    public function update(CalculationRequest $request, Calculation $calculation)
    {
        try {
            $validatedData = $request->validated();

            $calculation->update($validatedData);

            return redirect()->route('calculations.index')->with('success', 'Successfully editing calculation.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors('Error in editing calculation.\n errors::' . $e->getMessage())->withInput();
        }
    }

    /**
     * Bring The mounts
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-
     * @return \Illuminate\Http\RedirectResponse to view page
     */
    public function bring(User $driver)
    {
        try {
            $bringCount = $driver->calculations()->where('is_bring', false)->count();

            if($bringCount == 0) {
                return redirect()->route('drivers.index')->with('success', 'The driver has no outstanding payments to bring.');
            }

            $driver->calculations()->where('is_bring', false)
                ->update(['is_bring' => true]);

            return redirect()->route('drivers.index')->with('success', 'The outstanding payments have been successfully marked as brought.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors('Error bringing payments. Please try again. Error details: ' . $e->getMessage())->withInput();
        }
    }



    /**
     * Remove the specified resource from storage.
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-
     * @return \Illuminate\Http\RedirectResponse to view page
     */
    public function destroy(Calculation $calculation)
    {
        try {
            $calculation->delete();

            return redirect()->route('calculations.index')->with('success', 'Successfully calculation deleted.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors('Error in deleted calculation.'."\n errors:".$e->getMessage())->withInput();
        }
    }
}

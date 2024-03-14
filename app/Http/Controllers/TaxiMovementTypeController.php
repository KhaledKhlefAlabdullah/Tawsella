<?php

namespace App\Http\Controllers;

use App\Models\TaxiMovementType;
use Exception;
use Illuminate\Http\Request;

use function Laravel\Prompts\error;

class TaxiMovementTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{

            $movemntTypes = TaxiMovementType::all()->select('id', 'type', 'price');

            if(request()->wantsJson())
                return api_response(data:$movemntTypes,message:'getting-movement-type-error');
            
            return view('');

        }
        catch(Exception $e){
            if(request()->wantsJson())
                return api_response(errors:[$e->getMessage()],message:'getting-movement-type-success',code:500);
            return abort(message:'there error in getting the movements type',code:500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(TaxiMovementType $taxiMovementType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TaxiMovementType $taxiMovementType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TaxiMovementType $taxiMovementType)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TaxiMovementType $taxiMovementType)
    {
        //
    }
}

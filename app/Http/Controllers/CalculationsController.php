<?php

namespace App\Http\Controllers;

use App\Models\calculations;
use Exception;
use Illuminate\Http\Request;

class CalculationsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{

        }
        catch(Exception $e){
            return 
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
    public function show(calculations $calculations)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(calculations $calculations)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, calculations $calculations)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(calculations $calculations)
    {
        //
    }
}

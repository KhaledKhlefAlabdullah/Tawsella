<?php

namespace App\Http\Controllers;

use App\Enums\PaymentTypesEnum;
use App\Http\Requests\TaxiMovements\TaxiMovementsTypesRequest;
use App\Models\TaxiMovementType;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TaxiMovementTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        $movementTypes = TaxiMovementType::where('is_general', false)->get();
        $generalMovementTypes = TaxiMovementType::where('is_general', true)->get();

        if (request()->wantsJson())
            return api_response(data: $movementTypes, message: __('getting-movements-types-success'));
        //todo must change to movementTypes

        return view('services', ['movementTypes' => $generalMovementTypes, 'movements' => $movementTypes]);
    }

    /**
     * Get Taxi movement type details
     * @param TaxiMovementType $movement_type
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(TaxiMovementType $movement_type)
    {
        return api_response(data: $movement_type, message: __('getting-movements-type-details-success'));
    }

    /**
     * Show the form for creating a new resource.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function create()
    {
        //todo must change to movementTypes
        return view('service.create', ['paymentTypes' => PaymentTypesEnum::asArray()]);
    }

    /**
     * Store a newly created resource in storage.
     * @param TaxiMovementsTypesRequest $request is the new movement type details
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(TaxiMovementsTypesRequest $request)
    {
        try {
            $validatedData = $request->validated();

            TaxiMovementType::create($validatedData);

            return redirect()->route('services')->with('success', __('taxi-movement-type-created-success'));
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (Exception $e) {
            return redirect()->back()->withErrors(__('taxi-movement-type-created-error')."\n errors:" . $e->getMessage())->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param TaxiMovementType $movementType
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function edit(TaxiMovementType $movementType)
    {
        //todo must change to movementTypes
        return view('service.edit', ['movementType' => $movementType]);
    }

    /**
     * Update taxi movement type details
     * @param TaxiMovementsTypesRequest $request
     * @param TaxiMovementType $movementType
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(TaxiMovementsTypesRequest $request, TaxiMovementType $movementType)
    {
        try {

            $data = $request->validated();

            $movementType->update($data);

            return redirect()->route('services')->with('success', __('taxi-movement-type-edited-success'));
        } catch (Exception $e) {
            return redirect()->back()->withErrors(__('taxi-movement-type-edited-error') . "\n errors:" . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param TaxiMovementType $movementType
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(TaxiMovementType $movementType)
    {
        try {

            $movementType->delete();

            return redirect()->route('services')->with('success', __('movement-type-delete-success'));
        } catch (Exception $e) {
            return redirect()->back()->withErrors(__('movement-type-delete-error') . "\n errors:" . $e->getMessage());
        }
    }
}

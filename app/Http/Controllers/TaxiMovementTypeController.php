<?php

namespace App\Http\Controllers;

use App\Enums\PaymentTypesEnum;
use App\Http\Requests\TaxiMovements\TaxiMovementsTypesRequest;
use App\Models\TaxiMovementType;
use App\Services\PaginationService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class TaxiMovementTypeController extends Controller
{

    protected $paginationService;

    public function __construct(PaginationService $paginationService)
    {
        $this->paginationService = $paginationService;
    }

    /**
     * Display a listing of the resource.
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $query = TaxiMovementType::query()->where('is_general', false);

        $movementTypes = $this->paginationService->applyPagination($query, $request);

        $generalMovementTypes = TaxiMovementType::where('is_general', true)->get();

        $message = 'Successfully getting movements types';
        return api_response(
            data: [
                'movementTypes' => TaxiMovementType::mappingMovementTypes($generalMovementTypes),
                'movements' => TaxiMovementType::mappingMovementTypes($movementTypes->items())
            ],
            message: $message,
            pagination: get_pagination($movementTypes, $request));
    }

    /**
     * Get Taxi movement type details
     * @param TaxiMovementType $movement_type
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(TaxiMovementType $movement_type)
    {
        return api_response(data: TaxiMovementType::mappingSingleMovementType($movement_type), message: 'Successfully getting movements types details.');
    }

    /**
     * Store a newly created resource in storage.
     * @param TaxiMovementsTypesRequest $request is the new movement type details
     * @return JsonResponse
     */
    public function store(TaxiMovementsTypesRequest $request)
    {
        try {
            $validatedData = $request->validated();
            $validatedData['payment'] = PaymentTypesEnum::getValue($validatedData['payment']);
            $movement_type = TaxiMovementType::create($validatedData);
            return api_response(data: TaxiMovementType::mappingSingleMovementType($movement_type), message: 'Successfully creating movements type');
        } catch (Exception $e) {
            return api_response(errors: [$e->getMessage()], message: 'Error in creating movements type', code: 500);
        }
    }

    /**
     * Update taxi movement type details
     * @param TaxiMovementsTypesRequest $request
     * @param TaxiMovementType $movement_type
     * @return JsonResponse
     */
    public function update(TaxiMovementsTypesRequest $request, TaxiMovementType $movement_type)
    {
        try {
            $validatedData = $request->validated();
            if (isset($validatedData['payment']))
                    $validatedData['payment'] = PaymentTypesEnum::getValue($validatedData['payment']);
            $movement_type->update($validatedData);
            return api_response(data: TaxiMovementType::mappingSingleMovementType($movement_type), message: 'Successfully updating movements type');
        } catch (Exception $e) {
            return api_response(errors: [$e->getMessage()], message: 'Error in updating movements type', code: 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param TaxiMovementType $movement_type
     * @return JsonResponse
     */
    public function destroy(TaxiMovementType $movement_type)
    {
        try {
            $movement_type->delete();
            return api_response(message: 'Successfully deleting movements type');
        } catch (Exception $e) {
            return api_response(errors: [$e->getMessage()], message: 'Error in deleting movements type', code: 500);
        }
    }
}

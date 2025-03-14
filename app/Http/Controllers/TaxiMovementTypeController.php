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
     * @return JsonResponse
     */
    public function getKMPrice()
    {
        $KMprice = TaxiMovementType::where('is_onKM', true)
            ->select([
                'price1',
                'payment1',
                'price2',
                'payment2',
            ])->first();

        if (!$KMprice) {
            return api_response(message: 'لا يوجد سعر للكيلومتر حالياً', code: 404);
        }

        $data = [
            'price1' => $KMprice->price1,
            'payment1' => PaymentTypesEnum::getKey($KMprice->payment1),
            'price2' => $KMprice->price2,
            'payment2' => PaymentTypesEnum::getKey($KMprice->payment2)
        ];

        return api_response(data: $data, message: 'تم جلب سعر الكيلومتر بنجاح');
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

        $message = 'تم جلب انواع الرحلات بنجاح';
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
        return api_response(data: TaxiMovementType::mappingSingleMovementType($movement_type), message: 'تم جلب تفاصيل نوع الطلب بنجاح');
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
            if (array_key_exists('payment1', $validatedData)) {
                $validatedData['payment1'] = PaymentTypesEnum::getValue($validatedData['payment1']);
            }
            if (array_key_exists('payment2', $validatedData)) {
                $validatedData['payment2'] = PaymentTypesEnum::getValue($validatedData['payment2']);
            }
            $movement_type = TaxiMovementType::create($validatedData);
            return api_response(data: TaxiMovementType::mappingSingleMovementType($movement_type), message: 'تم اضافة نوع رحلة جديد');
        } catch (Exception $e) {
            return api_response(message: 'هناك خطأ في اضافة نوع رحلة جديد', code: 500, errors: [$e->getMessage()]);
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
            if (array_key_exists('payment1', $validatedData)) {
                $validatedData['payment1'] = PaymentTypesEnum::getValue($validatedData['payment1']);
            }
            if (array_key_exists('payment2', $validatedData)) {
                $validatedData['payment2'] = PaymentTypesEnum::getValue($validatedData['payment2']);
            }
            $movement_type->update($validatedData);
            return api_response(data: TaxiMovementType::mappingSingleMovementType($movement_type), message: 'تم تحديث نوع الطلب بنجاح');
        } catch (Exception $e) {
            return api_response(message: 'هناك خطأ في تحديث نوع الطلب', code: 500, errors: [$e->getMessage()]);
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

            if($movement_type->is_general)
                return api_response(message: 'لا يمكن حذف أنواع الرحلات الأساسية', code: 404);

            $movement_type->delete();
            return api_response(message: 'تم حذف نوع الرحلة بنجاح');
        } catch (Exception $e) {
            return api_response(message: 'خطأ في حذف نوع الرحلة', code: 500, errors: [$e->getMessage()]);
        }
    }
}

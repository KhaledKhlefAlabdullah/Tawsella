<?php

namespace App\Http\Controllers;

use App\Enums\UserEnums\UserType;
use App\Http\Requests\OffersRequest;
use App\Models\Offer;
use App\Models\TaxiMovementType;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class OfferController extends Controller
{
    /**
     * Display a listing of the resource.
     * Retrieves and displays both valid and ended offers.
     * Valid offers are those whose validity date is today or in the future.
     * Ended offers are those whose validity date is in the past.
     * @return JsonResponse|JsonResponse Returns a JSON response containing valid and ended offers.
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-21 - T-22
     */
    public function index()
    {
        try {

            $validOffers = Offer::getOffers();

            $authUser = Auth::user();
            if (!$authUser->hasRole(UserType::Admin()->key))
                return api_response(data: $validOffers, message: 'تم الحصول على العروض بنجاح');

            $endedOffers = Offer::getOffers('<');
            $response = ['validOffers' => $validOffers, 'endedOffers' => $endedOffers];

            return api_response(data: $response, message: 'Successfully getting offers');
        } catch (Exception $e) {
            return api_response(errors: [$e->getMessage()], message: 'Faild to get offers', code: 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     * Validates the request data and creates a new offer in the database.
     * @param OffersRequest $request The request object containing all the necessary data.
     * @return JsonResponse
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-23
     */
    public function store(OffersRequest $request)
    {
        try {

            $validatedData = $request->validated();

            $offer = Offer::create($validatedData);

            return api_response(data: $offer, message: 'Successfully created offer.');
        } catch (Exception $e) {
            return api_response(errors: [$e->getMessage()],message: 'Error in creating offer.', code: 500);
        }
    }

    /**
     * Display the specified resource.
     * @param Offer $offer
     * @return JsonResponse
     */
    public function show(Offer $offer)
    {
        return api_response(data: Offer::getOfferDetails($offer), message: 'Successfully getting offer details');
    }

    /**
     * Update the specified resource in storage.
     * Validates the request data and updates the specified offer.
     * @param OffersRequest $request The request object containing all the necessary data.
     * @param Offer $offer The ID of the offer to update.
     * @return JsonResponse Returns a JSON response with the updated offer or an error message.
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-24
     */
    public function update(OffersRequest $request, Offer $offer)
    {
        try {
            $validatedData = $request->validated();

            $offer->update($validatedData);

            return api_response(data: $offer, message: 'Successfully updated offer.');
        } catch (Exception $e) {
            return api_response(errors: [$e->getMessage()], message: 'Error in updating offer.', code: 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * Deletes the specified offer from the database.
     * @param Offer $offer The ID of the offer to delete.
     * @return JsonResponse Returns a JSON response indicating success or failure of the deletion.
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-25
     */
    public function destroy(Offer $offer)
    {
        try {
            $offer->delete();

            return api_response(message: 'Successfully deleted offer.');
        } catch (Exception $e) {
            return api_response(errors: [$e->getMessage()],message: 'Error in deleting error.', code: 500);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Enums\UserType;
use App\Http\Requests\OffersRequest;
use App\Models\Offer;
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
     * @return JsonResponse Returns a JSON response containing valid and ended offers.
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-21 - T-22
     */
    public function index()
    {
        try {
            $validOffers = Offer::getOffers();

            $response = ['valiedOffers' => $validOffers];
            $user = Auth::user();
            if ($user && $user->hasRole(UserType::ADMIN()->key)) {
                $endedOffers = Offer::getOffers('<');
                $response['endedOffers'] = $endedOffers;
            }

            return api_response(data: $response, message: 'Offers retrieved successfully');

        } catch (Exception $e) {
            return api_response(errors: $e->getMessage(), message: 'Failed to get offers data', code: 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     * Validates the request data and creates a new offer in the database.
     * @param OffersRequest $request The request object containing all the necessary data.
     * @return JsonResponse Returns a JSON response with the newly created offer or an error message.
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-23
     */
    public function store(OffersRequest $request)
    {
        try {
            $validatedData = $request->validated();

            Offer::create($validatedData);

            return api_response(message: 'Offer created successfully');
        } catch (Exception $e) {
            return api_response(errors: $e->getMessage(), message: 'Offer created error', code: 500);
        }
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

            return api_response(message: 'Offer updated successfully');
        } catch (Exception $e) {
            return api_response(errors: [$e->getMessage()], message: 'Offer updated error', code: 500);
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

            // Return a success message indicating the offer was deleted
            return api_response(message: 'Offer deleted successfully');
        } catch (Exception $e) {
            // Return an error response if an exception occurs
            return api_response(errors: $e->getMessage(), message: 'Offer deleted error', code: 500);
        }
    }
}

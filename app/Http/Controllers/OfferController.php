<?php

namespace App\Http\Controllers;

use App\Http\Requests\OffersRequest;
use App\Models\Offer;
use App\Models\MovementType;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OfferController extends Controller
{
    /**
     * Display a listing of the resource.
     * Retrieves and displays both valid and ended offers.
     * Valid offers are those whose validity date is today or in the future.
     * Ended offers are those whose validity date is in the past.
     *
     * @return mixed Returns a JSON response containing valid and ended offers.
     */
    public function index()
    {
        try {

            $valiedOffers = Offer::select('offers.id', 'offers.title', 'offers.valide_date', 'offers.discreption', 'up.name', 'up.avatar')
                ->join('user_profiles as up', 'offers.user_id', '=', 'up.user_id')
                ->where('offers.valide_date', '>=', now())
                ->get();

            if (Auth::user()->user_type != 'admin')
                return api_response(data: $valiedOffers, message: 'تم الحصول على العروض بنجاح');

            $endedOffers = Offer::select('offers.id', 'offers.title', 'offers.valide_date', 'offers.discreption', 'up.name', 'up.avatar')
                ->join('user_profiles as up', 'offers.user_id', '=', 'up.user_id')
                ->where('offers.valide_date', '<', now())
                ->get();

            return api_response(data: ['valiedOffers' => $valiedOffers, 'endedOffers' => $endedOffers], message: 'تم الحصول على العروض بنجاح');
        } catch (Exception $e) {
            return api_response(errors: $e->getMessage(), message: 'فشل الحصول على العروض', code: 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     * Validates the request data and creates a new offer in the database.
     *
     * @param OffersRequest $request The request object containing all the necessary data.
     * @return mixed Returns a JSON response with the newly created offer or an error message.
     */
    public function store(OffersRequest $request)
    {
        try {
            $validatedData = $request->validated();


            Offer::create($validatedData);

            return api_response(message: 'تم انشاء العرض بنجاح');
        } catch (Exception $e) {
            return api_response(errors: $e->getMessage(), message: 'فشل انشاء العرض', code: 500);
        }
    }


    /**
     * Update the specified resource in storage.
     * Validates the request data and updates the specified offer.
     *
     * @param OffersRequest $request The request object containing all the necessary data.
     * @param string $id The ID of the offer to update.
     * @return mixed Returns a JSON response with the updated offer or an error message.
     */
    public function update(OffersRequest $request, string $id)
    {
        try {
            $validatedData = $request->validated();

            $offer = getAndCheckModelById(Offer::class, $id);
            $offer->update($validatedData);

            return api_response(message: 'تم تعديل العرض بنجاح');
        } catch (Exception $e) {
            return api_response(errors: $e->getMessage(), message: 'فشل تعديل العرض', code: 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * Deletes the specified offer from the database.
     *
     * @param string $id The ID of the offer to delete.
     * @return mixed Returns a JSON response indicating success or failure of the deletion.
     */
    public function destroy(string $id)
    {
        try {

            $offer = getAndCheckModelById(Offer::class, $id);

            $offer->delete();

            // Return a success message indicating the offer was deleted
            return api_response(message: 'تم حذف العرض بنجاح');
        } catch (Exception $e) {
            // Return an error response if an exception occurs
            return api_response(errors: $e->getMessage(), message: 'فشل حذف العرض', code: 500);
        }
    }
}

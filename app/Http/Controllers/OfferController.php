<?php

namespace App\Http\Controllers;

use App\Enums\UserEnums\UserType;
use App\Http\Requests\OffersRequest;
use App\Models\Offer;
use App\Models\TaxiMovementType;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OfferController extends Controller
{
    /**
     * Display a listing of the resource.
     * Retrieves and displays both valid and ended offers.
     * Valid offers are those whose validity date is today or in the future.
     * Ended offers are those whose validity date is in the past.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|JsonResponse Returns a JSON response containing valid and ended offers.
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-21 - T-22
     */
    public function index()
    {
        try {

            $validOffers = Offer::getOffers();

            if (request()->wantsJson())
                return api_response(data: $validOffers, message: 'تم الحصول على العروض بنجاح');

            $response = ['validOffers' => $validOffers];
            $user = Auth::user();

            if ($user && $user->hasRole(UserType::Admin()->key)) {
                $endedOffers = Offer::getOffers('<');
                $response['endedOffers'] = $endedOffers;
            }

            return view('offers.index', ['offers' => $response]);
        } catch (Exception $e) {
            if (request()->wantsJson())
                return api_response(errors: $e->getMessage(), message: 'Faild to get offers', code: 500);
            return redirect()->back()->withErrors('Faild to get offers.\n errors:' . $e->getMessage())->withInput();
        }
    }

    /**
     * Store a newly created resource in storage.
     * Validates the request data and creates a new offer in the database.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-23
     */
    public function create()
    {
        $movementTypes = TaxiMovementType::all();
        $admins = User::where('user_type', 'admin')->get();

        return view('offers.create', ['movementTypes' => $movementTypes, 'admins' => $admins]);
    }

    /**
     * Store a newly created resource in storage.
     * Validates the request data and creates a new offer in the database.
     * @param OffersRequest $request The request object containing all the necessary data.
     * @return \Illuminate\Http\RedirectResponse
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-23
     */
    public function store(OffersRequest $request)
    {
        try {

            $validatedData = $request->validated();

            Offer::create($validatedData);

            return redirect()->route('offers.index')->with('success', 'Successfully created offer.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors('Error in creating offer.\n errors:' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     * @param Offer $offer
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function show(Offer $offer)
    {
        return view('offers.show', ['offer' => Offer::getOfferDetails($offer)]);
    }

    /**
     * Update the specified resource in storage.
     * Validates the request data and updates the specified offer.
     * @param Offer $offer The ID of the offer to update.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application Returns a JSON response with the updated offer or an error message.
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-24
     */
    public function edit(Offer $offer)
    {
        $movementTypes = TaxiMovementType::all();
        $admins = User::where('user_type', UserType::Admin)->get();

        return view('offers.edit', ['offer' => $offer, 'movementTypes' => $movementTypes, 'admins' => $admins]);
    }

    /**
     * Update the specified resource in storage.
     * Validates the request data and updates the specified offer.
     * @param OffersRequest $request The request object containing all the necessary data.
     * @param Offer $offer The ID of the offer to update.
     * @return \Illuminate\Http\RedirectResponse Returns a JSON response with the updated offer or an error message.
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-24
     */
    public function update(OffersRequest $request, Offer $offer)
    {
        try {
            $validatedData = $request->validated();

            $offer->update($this->validate());

            return redirect()->route('offers.index')->with('success', 'Successfully updated offer.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors('Error in updating offer.\n errors:' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     * Deletes the specified offer from the database.
     * @param Offer $offer The ID of the offer to delete.
     * @return \Illuminate\Http\RedirectResponse Returns a JSON response indicating success or failure of the deletion.
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-25
     */
    public function destroy(Offer $offer)
    {
        try {
            $offer->delete();

            return redirect()->route('offers.index')->with('success', 'Successfully deleted offer.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors('Error in deleting error.\n errors:' . $e->getMessage())->withInput();
        }
    }
}

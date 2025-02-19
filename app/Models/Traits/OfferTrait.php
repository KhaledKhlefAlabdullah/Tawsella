<?php

namespace App\Models\Traits;

use App\Models\Offer;

trait OfferTrait
{
    /**
     * Return the offers with movements type
     * @param string $cond is the condition will apply on the valid date
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
     */
    public static function getOffers(string $cond = '>=')
    {
        $offers = Offer::with('movement_type_offer')
            ->where('valid_date', $cond, now())
            ->orderBy('created_at', 'desc')
            ->get();

        return $offers->map(function ($offer) {
            return [
                'id' => $offer->id,
                'offer' => $offer->offer,
                'value_of_discount' => $offer->value_of_discount,
                'valid_date' => $offer->valid_date,
                'type' => $offer->movement_type_offer->type ?? null,
                'price1' => $offer->movement_type_offer->price1 ?? null,
                'payment1' => $offer->movement_type_offer->payment1 ?? null,
                'price2' => $offer->movement_type_offer->price2 ?? null,
                'payment2' => $offer->movement_type_offer->price2 ?? null,
                'description' => $offer->description ?? null
            ];
        });
    }

    /**
     * Get offer details
     * @param Offer $offer
     * @return array of offer details
     */
    public static function getOfferDetails(Offer $offer)
    {
            return [
                'id' => $offer->id,
                'offer' => $offer->offer,
                'value_of_discount' => $offer->value_of_discount,
                'valid_date' => $offer->valid_date,
                'type' => $offer->movement_type_offer->type ?? null,
                'price' => $offer->movement_type_offer->price ?? null,
                'description' => $offer->movement_type_offer->description ?? null
            ];
    }
}

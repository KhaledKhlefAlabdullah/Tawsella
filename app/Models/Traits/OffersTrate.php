<?php

namespace App\Models\Traits;

use App\Models\Offer;

trait OffersTrate
{
    public static function getOffers(string $cond = '>=')
    {
        $data = Offer::with('user.profile:user_id,name,avatar')
            ->where('offers.valid_date', $cond, now())
            ->get();
        $data = $data->map(function ($offer) {
            $profile = $offer->user->profile;
            return [
                'user_name' => $profile->name,
                'user_avatar' => $profile->avatar,
                'offer_id' => $offer->id,
                'title' => $offer->title,
                'valid_date' => $offer->valid_date,
                'description' => $offer->description,
                'created_at' => $offer->created_at
            ];
        });

        return $data;
    }
}

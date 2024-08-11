<?php

use App\Models\User;
use App\Notifications\TawsellaNotification;
use App\Notifications\Test;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // $user = getAndCheckModelById(User::class, getAdminId());
    // $user_profile = $user
    // ? (object) [
    //     'email' => $user->email,
    //     'name' => $user->profile->name,
    //     'avatar_url' => $user->profile->avatar
    // ]
    // : (object) [
    //     'email' => 'default@example.com',
    //     'name' => 'Anonymous',
    //     'avatar_url' => 'images/profile_images/default_user_avatar.png'
    // ];

    // Notification::send([$user], new TawsellaNotification($user_profile, 'test twasella', [$user]));

    return ['Laravel' => app()->version()];
});

require __DIR__.'/auth.php';

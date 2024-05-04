<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::group(['middleware' => ['auth:sanctum', 'verify.email']], function() {

    // admin routes
    require __DIR__.'./Roles/admin.php';

    // customer routes
    require __DIR__.'./Roles/customer.php';

    // driver routes
    require __DIR__.'./Roles/driver.php';

    // public routes
    require __DIR__.'./Roles/publicApis.php';


});



require __DIR__ . '/auth.php';


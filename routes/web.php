<?php


use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/', function () {
    return view('welcom');
});

//***************************start route AppPlatform ******************************** */
Route::get('/AppPlatform', function () {
    return view('ApplicationPlatform');
});
//*****************************End route AppPlatform ******************************** */
//*********************************************************************************** */

require __DIR__.'/Roles/admin.php';

require __DIR__ . '/auth.php';

<?php

use App\Http\Controllers\PaypalController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('paypal')->group(function(){
    Route::post('order/create', [PaypalController::class, 'createOrder'])->name('create.order');
    Route::post('order/capture', [PaypalController::class, 'captureOrder'])->name('capture.order');
});

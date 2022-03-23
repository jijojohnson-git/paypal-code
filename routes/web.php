<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaypalController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::prefix('paypal')->group(function(){
    Route::get('order/process', [PaypalController::class, 'processOrder'])->name('process.order');
    Route::get('order/success', [PaypalController::class, 'processSuccess'])->name('process.success');
    Route::get('order/cancel', [PaypalController::class, 'processCancel'])->name('process.cancel');
});

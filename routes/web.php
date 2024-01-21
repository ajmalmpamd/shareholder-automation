<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShareholderController;
use App\Http\Controllers\PaymentController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|


Route::get('/', function () {
    return view('welcome');
});*/
Route::get('/', [ShareholderController::class, 'index']);
Route::get('/all-payments', [PaymentController::class, 'index'])->name('all-payments');
Route::resource('shareholders', ShareholderController::class);
Route::get('/shareholders/create-payments/{eid}', [ShareholderController::class, 'create_payments'])->name('shareholders.create-payments');
Route::post('/shareholders/create-payments/{eid}', [PaymentController::class, 'store'])->name('shareholders.store-payments');
Route::get('/shareholders/payments/{eid}', [PaymentController::class, 'shareholder_payments'])->name('shareholders.payments');
Route::post('/shareholders/make-payments/{eid}', [PaymentController::class, 'make_payments'])->name('shareholders.make-payments');



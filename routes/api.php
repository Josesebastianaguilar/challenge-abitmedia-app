<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SoftwareProductController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\SoftwareProductLicenseController;
use App\Http\Controllers\Api\SoftwareProductPriceController;
use App\Http\Controllers\Api\UserController;

/* Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum'); */

Route::get('/', function () {
    return response()->json(['success' => true, 'message' => 'Api Rest Laravel 11']);
});
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/user-profile', [UserController::class, 'getAuthenticatedUser']);
    Route::get('/logout', [UserController::class, 'logout']);
    Route::resource('/software-products', SoftwareProductController::class);
    Route::resource('/services', ServiceController::class);
    Route::resource('/software-product-lincese', SoftwareProductLicenseController::class);
    Route::resource('/software-product-price', SoftwareProductPriceController::class);
});

/* Route::middleware('auth:sanctum')->group(function () {
    // Software Product Routes
    Route::resource('software-products', SoftwareProductController::class);

    // Service Routes
    Route::resource('services', ServiceController::class);

    // Other Resource Routes...
}); */

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SoftwareProductController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\SoftwareProductLicenseController;
use App\Http\Controllers\Api\SoftwareProductPriceController;
use App\Http\Controllers\Api\OperativeSystemController;
use App\Http\Controllers\Api\UserController;

/* Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum'); */

Route::get('/', function () {
    return response()->json(['success' => true, 'message' => 'Api Rest Laravel 11']);
});
Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'login']);
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('user-profile', [UserController::class, 'getAuthenticatedUser']);
    Route::get('logout', [UserController::class, 'logout']);
    // Software Product Routes
    Route::get('software-products', [SoftwareProductController::class, 'index']);
    Route::get('software-products/{sku}', [SoftwareProductController::class, 'show']);
    Route::post('software-products', [SoftwareProductController::class, 'store']);
    Route::put('software-products/{sku}', [SoftwareProductController::class, 'update']);
    Route::delete('software-products/{sku}', [SoftwareProductController::class, 'destroy']);
    // Software Product License Routes
    Route::get('software-products/{sku}/licenses', [SoftwareProductController::class, 'softwareProductLicenses']);
    Route::get('software-products/licenses/all', [SoftwareProductController::class, 'generalSoftwareProductLicenses']);
    Route::get('software-products/licenses/show/{serial}', [SoftwareProductController::class, 'softwareProductLicensesShow']);
    Route::post('software-products/{sku}/licenses/{os_slug}', [SoftwareProductController::class, 'softwareProductLicensesStore']);
    Route::post('software-products/licenses', [SoftwareProductController::class, 'generalSoftwareProductLicensesStore']);
    Route::put('software-products/licenses/{serial}', [SoftwareProductController::class, 'softwareProductLicenseUpdate']);
    Route::delete('software-products/licenses/{serial}', [SoftwareProductController::class, 'destroySoftwareProductLicense']);
    // Software Product Price Routes
    Route::get('software-products/prices/all', [SoftwareProductController::class, 'generalSoftwareProductPrices']);
    Route::get('software-products/{sku}/prices', [SoftwareProductController::class, 'generalSoftwareProductPrices']);
    Route::get('software-products/prices/{id}', [SoftwareProductController::class, 'generalSoftwareProductPrices']);
    Route::post('software-products/{sku}/prices/{os_slug}', [SoftwareProductController::class, 'softwareProductLicensesStore']);
    Route::post('software-products/prices', [SoftwareProductController::class, 'generalSoftwareProductLicensesStore']);
    Route::put('software-products/prices/{serial}', [SoftwareProductController::class, 'softwareProductLicenseUpdate']);
    Route::delete('software-products/licenses/{serial}', [SoftwareProductController::class, 'destroySoftwareProductLicense']);
    // Service Routes
    Route::get('services', [ServiceController::class, 'index']);
    Route::get('services/{sku}', [ServiceController::class, 'show']);
    Route::post('services', [ServiceController::class, 'store']);
    Route::put('services/{sku}', [ServiceController::class, 'update']);
    Route::delete('services/{sku}', [ServiceController::class, 'destroy']);
    // Operative System Routes
    Route::get('operative-systems', [OperativeSystemController::class, 'index']);
    Route::get('operative-systems/{slug}', [OperativeSystemController::class, 'show']);
    Route::post('operative-systems', [OperativeSystemController::class, 'store']);
    Route::put('operative-systems/{slug}', [OperativeSystemController::class, 'update']);
    Route::delete('operative-systems/{slug}', [OperativeSystemController::class, 'destroy']);
    /* Route::resource('software-product-lincese', SoftwareProductLicenseController::class);
    Route::resource('software-product-price', SoftwareProductPriceController::class); */
});

/* Route::middleware('auth:sanctum')->group(function () {
    // Software Product Routes
    Route::resource('software-products', SoftwareProductController::class);

    // Service Routes
    Route::resource('services', ServiceController::class);

    // Other Resource Routes...
}); */

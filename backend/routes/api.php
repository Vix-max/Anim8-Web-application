<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\AuthController;

// Existing route
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// New route for customer registration
Route::post('/register/customer', [CustomerController::class, 'registerCustomer']);
Route::post('/login/customer', [AuthController::class, 'loginCustomer']);

Route::middleware('auth:sanctum')->group(function () {
    
    // Route to get logged-in customer profile
    Route::get('/customer/profile', [CustomerController::class, 'getCustomerProfile']);
    
    // Route to update logged-in customer profile
    Route::put('/customer/profile/update', [CustomerController::class, 'updateCustomerProfile']);
});
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;

// Existing route
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// New route for customer registration
Route::post('/register/customer', [CustomerController::class, 'registerCustomer']);
Route::post('/login/customer', [AuthController::class, 'loginCustomer']);

// New route for admin registration
Route::post('/register/admin', [AdminController::class, 'registerAdmin']);
Route::post('/login/admin', [AuthController::class, 'loginAdmin']);

Route::get('/products/getAllProducts', [ProductController::class, 'getAllProducts']);
Route::post('/products/add', [ProductController::class, 'addProduct']);
Route::get('/products/getProduct/{id}', [ProductController::class, 'getProductById']);

Route::post('/chatbot/predict-intent', function (Request $request) {
    $response = Http::post('http://localhost:5000/chatbot/predict', [
        'instruction' => $request->input('instruction'),
    ]);
    
    return response()->json($response->json());
});



Route::middleware('auth:sanctum')->group(function () {
    
    // Route to get logged-in customer profile
    Route::get('/customer/profile', [CustomerController::class, 'getCustomerProfile']);
    
    // Route to update logged-in customer profile
    Route::put('/customer/profile/update', [CustomerController::class, 'updateCustomerProfile']);

    // Route to get logged-in admin profile
    Route::get('/admin/profile', [AdminController::class, 'getAdminProfile']);
    
    // Route to update logged-in admin profile
    Route::put('/admin/profile/update', [AdminController::class, 'updateAdminProfile']);
});



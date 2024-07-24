<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\auth\AuthController;
use App\Http\Controllers\api\roles\RoleController;
use App\Http\Controllers\api\customers\CustomerController;
use App\Http\Controllers\api\orders\OrderController;

// Register & login routes
Route::post('auth/register', [AuthController::class, 'register']);
Route::post('auth/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Logout route
    Route::post('auth/logout', [AuthController::class, 'logout']);

    // Roles routes
    Route::post('roles/create', [RoleController::class, 'create']);


    // Customers routes
    Route::post('customers/create', [CustomerController::class, 'create']);
    Route::get('customers/index', [CustomerController::class, 'index']);
    Route::put('customers/update/{id}', [CustomerController::class, 'update']);
    Route::delete('customers/delete/{id}', [CustomerController::class, 'delete']);

    // Orders routes
    Route::post('orders/create', [OrderController::class, 'create']);
    Route::get('orders/index', [OrderController::class, 'index']);
    Route::put('orders/update/{id}', [OrderController::class, 'update']);
    Route::delete('orders/delete/{id}', [OrderController::class, 'delete']);
    
});

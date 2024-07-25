<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\auth\AuthController;
use App\Http\Controllers\api\roles\RoleController;
use App\Http\Controllers\api\customers\CustomerController;
use App\Http\Controllers\api\orders\OrderController;
use App\Http\Controllers\api\categories\CategoryController;
use App\Http\Controllers\api\suppliers\SupplierController;

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

    // Categories routes
    Route::post('categories/create', [CategoryController::class, 'create']);
    Route::get('categories/index', [CategoryController::class, 'index']);
    Route::put('categories/update/{id}', [CategoryController::class, 'update']);
    Route::delete('categories/delete/{id}', [CategoryController::class, 'delete']);

    // Suppliers routes
    Route::post('suppliers/create', [SupplierController::class, 'create']);
    Route::get('suppliers/index', [SupplierController::class, 'index']);
    Route::put('suppliers/update/{id}', [SupplierController::class, 'update']);
    Route::delete('suppliers/delete/{id}', [SupplierController::class, 'delete']);
    
});

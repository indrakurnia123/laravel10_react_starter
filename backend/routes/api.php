<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\MenuController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// API Version 1
Route::prefix('v1')->group(function () {
    
    // Authentication routes
    Route::prefix('auth')->group(function () {
        Route::post('login', [AuthController::class, 'login']);
        Route::post('register', [AuthController::class, 'register']);
        Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
        Route::post('reset-password', [AuthController::class, 'resetPassword']);
        
        // Protected auth routes
        Route::middleware('auth:sanctum')->group(function () {
            Route::get('me', [AuthController::class, 'me']);
            Route::post('logout', [AuthController::class, 'logout']);
            Route::post('refresh', [AuthController::class, 'refresh']);
            Route::put('profile', [AuthController::class, 'updateProfile']);
            Route::put('change-password', [AuthController::class, 'changePassword']);
        });
    });
    
    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        
        // Menu routes
        Route::prefix('menus')->group(function () {
            Route::get('/', [MenuController::class, 'index']);
            Route::get('all', [MenuController::class, 'all']);
            Route::post('/', [MenuController::class, 'store']);
            Route::put('{menu}', [MenuController::class, 'update']);
            Route::delete('{menu}', [MenuController::class, 'destroy']);
        });
        
        // User management routes
        Route::prefix('users')->group(function () {
            // Routes will be added as needed
        });
        
        // Role management routes
        Route::prefix('roles')->group(function () {
            // Routes will be added as needed
        });
        
        // Permission management routes
        Route::prefix('permissions')->group(function () {
            // Routes will be added as needed
        });
        
        // Notification routes
        Route::prefix('notifications')->group(function () {
            // Routes will be added as needed
        });
        
        // System settings routes
        Route::prefix('settings')->group(function () {
            // Routes will be added as needed
        });
        
    });
    
});

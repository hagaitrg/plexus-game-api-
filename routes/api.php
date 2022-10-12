<?php

use App\Http\Controllers\API\AchievementController;
use App\Http\Controllers\API\GameDataController;
use App\Http\Controllers\API\InventoryController;
use App\Http\Controllers\API\ShopController;
use App\Http\Controllers\Auth\AuthController;
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

Route::prefix('v1')->group(function(){
    Route::get('/', function(){
        return response()->json([
            "success" => true,
            "code" => 200,
            "message" => "Welcome to Plexu Game REST API V 1.0!" 
        ]);
    });
    Route::prefix('users')->group(function(){
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login']);
        Route::post('forget-password', [AuthController::class, 'forgetPassword']);
        Route::post('reset-password', [AuthController::class, 'resetPassword']);
        
        Route::get('my-profile', [AuthController::class, 'getMyProfile'])->middleware('auth:sanctum');
        Route::post('update-profile', [AuthController::class, 'updateProfile'])->middleware('auth:sanctum');

    });

    Route::middleware('auth:sanctum')->group(function(){
        Route::prefix('player')->group(function (){
            Route::prefix('status')->group(function (){
                Route::post('submit', [GameDataController::class,'submitGameData']);
                Route::get('my-status', [GameDataController::class,'getMyStatus']);
            });

            Route::prefix('shop')->group(function(){
                Route::get('all-item', [ShopController::class,'allItem']);
                Route::post('buy-item/{itemId}', [ShopController::class,'buyItem']);
            });

            Route::prefix('inventory')->group(function(){
                Route::get('my-inventory', [InventoryController::class, 'myInventories']);
            });

            Route::prefix('achievement')->group(function(){
                Route::get('my-achievement', [AchievementController::class, 'myAchievement']);
                Route::get('list-leaderboard', [AchievementController::class, 'listLeaderboard']);
            });
        });
    });
});

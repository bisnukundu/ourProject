<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserLevel;
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

Route::middleware('auth:sanctum')->get('/test', function (Request $request) {
    return "This is a protected route";
});



// This route for users 
Route::prefix("user")->group(function () {
    Route::post('/register', [UserController::class, 'userRegister'])->name('user.register');
    Route::post('/login', [UserController::class, 'userLogin'])->name('user.login');

    // Protected Route 
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('/logout', [UserController::class, 'userLogout'])->name('user.logout');
        Route::get('/referral_friend/{username}', [UserController::class, 'referralFriend'])->name('user.logout');
        Route::get('/level', [UserLevel::class, 'getLevel'])->name('user.level');
    });
});

// This is route for admin 
Route::prefix("admin")->group(function () {
    Route::post('/register', [AdminController::class, 'adminRegister']);
    Route::post('/login', [AdminController::class, 'adminLogin']);
});

<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BalanceHistoryController;
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
        Route::get('/my-team', [UserLevel::class, 'getTeam']);
        Route::get("/user/{id?}", [UserController::class, 'getUserById']);
        Route::get("/username/{username?}", [UserController::class, 'getUserByName']);
        Route::get('/balance-history', [AdminController::class, 'balanceHistory']);
    });
});


// This is route for admin
Route::prefix("admin")->group(function () {
    Route::post('/register', [AdminController::class, 'adminRegister']);
    Route::post('/login', [AdminController::class, 'adminLogin']);

    Route::middleware(['auth:admins'])->group(function () {
        Route::put('/deactive-user/{id}', [AdminController::class, 'deactiveUser']);
        Route::delete('delete-user/{id}', [AdminController::class, 'deleteUser']);
        Route::get('/all-user/{username?}', [AdminController::class, 'getAllUser']);
        Route::put('/admin-send-balance', [AdminController::class, 'adminSendBalance']);
    });

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::put('/send-balance', [AdminController::class, 'sendBalance']);
        Route::put('/active-user/{id}', [AdminController::class, 'activeUser']);
    });
});

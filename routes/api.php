<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
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
    Route::post('/logout', [UserController::class, 'userLogout'])->name('name.logout')->middleware('auth:sanctum');
});

// This is route for admin 
Route::prefix("admin")->group(function () {
    Route::post('/register', [AdminController::class, 'adminRegister']);
    Route::post('/login', [AdminController::class, 'adminLogin']);
});

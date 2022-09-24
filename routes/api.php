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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get("test", function () {
    return "working";
});

// This route for users 
Route::prefix("user")->group(function () {
    Route::post('/register', [UserController::class, 'userRegister']);
    Route::post('/login', [UserController::class, 'userLogin']);
});

// This is route for admin 
Route::prefix("admin")->group(function () {
    Route::post('/register', [AdminController::class, 'adminRegister']);
    Route::post('/login', [AdminController::class, 'adminLogin']);
});

<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/auth/register', [RegisterController::class, 'register']);


// 登入路由
Route::post('/auth/login', [LoginController::class, 'login'])->middleware('set.access.cookie');
Route::middleware(['jwt.auth'])->group(function () {
    Route::post('/auth/logout', [LoginController::class, 'logout']);
    Route::post('/auth/refresh', [LoginController::class, 'refresh']);
    Route::get('/auth/me', [LoginController::class, 'me']);
});

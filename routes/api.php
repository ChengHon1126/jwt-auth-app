<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\DownFileController;
use App\Http\Controllers\Api\LessonPlanController;
use App\Http\Controllers\WorkController;
use App\Http\Controllers\Api\WorkRatingController;
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


// 登入路由
Route::middleware(['set.access.cookie'])->group(function () {
    Route::post('/auth/login', [LoginController::class, 'login']);
    Route::post('/auth/register', [RegisterController::class, 'register']);
});
Route::middleware(['jwt.cookie.auth', 'jwt.auth'])->group(function () {
    Route::post('/auth/logout', [LoginController::class, 'logout']);
    Route::post('/auth/refresh', [LoginController::class, 'refresh']);
    Route::get('/auth/me', [LoginController::class, 'me']);

    //瀏覽作品
    Route::get('/works', [DashboardController::class, 'work']);

    Route::get('/works/published', [WorkController::class, 'getAllPublishedWorks']);
    Route::get('/works/my', [WorkController::class, 'getMyWorks']);
    Route::post('/works/submit-for-review', [WorkController::class, 'submitForReview']);
    Route::get('/works/pennding', [WorkController::class, 'getPenndingWorks']); // 顯示待簽核資料
    Route::get('/works_show', [DashboardController::class, 'work_show']); // 顯示作品詳情
    Route::get('/works/collects', [WorkController::class, 'getCollectsWork']); // 顯示收藏
    Route::post('works/collects', [WorkController::class, 'toggleCollect']); // 收藏

    Route::get('/files/download', [DownFileController::class, 'downloadFile']);
    // 審核作品
    Route::get('/review', [AdminController::class, 'reviews']);
    Route::post('/works/approve', [AdminController::class, 'approveWork']);
    // 評論 
    Route::get('/works/comments', [WorkRatingController::class, 'getComments']);
    Route::post('/works/rate-and-comment', [WorkRatingController::class, 'rateAndComment']);

    // 上傳教案
    Route::post('/lesson-plans', [LessonPlanController::class, 'create']);
    Route::get('/lesson-plans', [LessonPlanController::class, 'index']);
    Route::put('/lesson-plans', [LessonPlanController::class, 'push']);
    Route::delete('/lesson-plans', [LessonPlanController::class, 'delete']);
});

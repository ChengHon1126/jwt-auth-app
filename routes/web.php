<?php

use App\Http\Controllers\CompetitionController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\WorkController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');
// 註冊頁面路由（如需要）
Route::get('/register', function () {
    return view('auth.register');
})->name('register');

// Route::get('/upload', [WorkController::class, 'create'])->name('upload');
Route::get('/upload', [WorkController::class, 'create'])->name('upload');
Route::post('/upload', [WorkController::class, 'store']);

Route::get('/upload_lesson', [LessonController::class, 'create'])->name('upload_lesson');
Route::post('/upload_lesson', [LessonController::class, 'store']);


Route::get('/competition/submit', [CompetitionController::class, 'create'])->name('competition.submit');
Route::post('/competition/submit', [CompetitionController::class, 'store']);

Route::get('/work/{id}', [WorkController::class, 'show'])->name('work.show');
Route::get('/lesson/{id}', [LessonController::class, 'show'])->name('lesson.show');
Route::get('/competition/{id}', [CompetitionController::class, 'show'])->name('competition.show');

<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CompetitionController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\LessonPlanController;
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



// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->name('dashboard');
// 註冊頁面路由（如需要）
Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::get('/login', function () {
    return view('auth.login');
})->name('login');
Route::middleware(['jwt.cookie.auth'])->group(function () {
    Route::get('/', function () {
        return view('dashboard');
    });
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/admin', function () {
        return view('admin');
    });

    Route::get('/upload', [WorkController::class, 'create'])->name('upload');
    Route::post('/upload', [WorkController::class, 'store']);
    Route::get('/works/{id}', [WorkController::class, 'show'])->name('work.show');

    // 後臺管理
    Route::get('/admin/review-works', function () {
        return view('admin.review-works');
    })->name('admin.review-works');
    Route::get('/admin/review-works/{id}', [AdminController::class, 'reviewShow'])->name('review-show');
    Route::get('/admin/lesson-plans/create', [AdminController::class, 'lessonPlancreate'])->name('lesson-plans.create');
    Route::get('/admin/review-lessons', [AdminController::class, 'reviewLessons'])->name('admin.review-lessons');


    // Route::get('/admin/review-works')->name('admin.review-works');
    Route::get('/admin/manage-events', [AdminController::class, 'manageEvents'])->name('admin.manage-events');


    // PDF 預覽
    Route::get('/pdf/{filename}', function ($filename) {
        $path = storage_path('app/public/original_pdfs/' . $filename);

        if (file_exists($path)) {
            return response()->file($path, [
                'Content-Type' => 'application/pdf'
            ]);
        }

        abort(404);
    })->name('pdf.view');
});




// Route::get('/upload', [WorkController::class, 'create'])->name('upload');



Route::get('/upload_lesson', [LessonController::class, 'create'])->name('upload_lesson');
Route::post('/upload_lesson', [LessonController::class, 'store']);


Route::get('/competition/submit', [CompetitionController::class, 'create'])->name('competition.submit');
Route::post('/competition/submit', [CompetitionController::class, 'store']);

Route::get('/lesson/{id}', [LessonController::class, 'show'])->name('lesson.show');
Route::get('/competition/{id}', [CompetitionController::class, 'show'])->name('competition.show');

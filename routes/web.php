<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NovelController;   // 🌟 Đã gọi lại Controller Truyện
use App\Http\Controllers\ChapterController; // 🌟 Đã gọi lại Controller Chương

// 🌟 1. CÁC ĐƯỜNG DẪN WEB TRUYỆN CỦA BẠN
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/tim-kiem', [HomeController::class, 'search'])->name('search');
Route::get('/the-loai/{slug}', [HomeController::class, 'category'])->name('category.show');

// 🌟 ĐÂY LÀ 2 ĐƯỜNG DẪN BỊ THIẾU VỪA ĐƯỢC BỔ SUNG
Route::get('/truyen/{slug}', [NovelController::class, 'show'])->name('novel.show');
Route::get('/truyen/{novel_slug}/{chapter_slug}', [ChapterController::class, 'show'])->name('chapter.show');


// 🌟 2. CÁC ĐƯỜNG DẪN TÀI KHOẢN CỦA BREEZE (Giữ nguyên)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
// Route xử lý việc bấm nút Theo dõi (bắt buộc phải đăng nhập mới cho bấm)
Route::post('/truyen/{id}/theo-doi', [App\Http\Controllers\NovelController::class, 'toggleFollow'])->name('novel.follow')->middleware('auth');
Route::get('/xep-hang', [App\Http\Controllers\HomeController::class, 'ranking'])->name('ranking');
Route::get('/theo-doi', [App\Http\Controllers\HomeController::class, 'following'])->name('following')->middleware('auth');
require __DIR__.'/auth.php';
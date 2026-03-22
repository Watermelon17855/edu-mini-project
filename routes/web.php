<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Client\CourseController;
use App\Http\Controllers\Client\PaymentController;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminCourseController;
use App\Http\Controllers\Admin\AdminLessonController;
use App\Http\Controllers\Admin\AdminUserController;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;

use App\Models\Course;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| CLIENT ROUTES (Dành cho học viên)
|--------------------------------------------------------------------------
*/

// 1. TRANG CHỦ: Hiển thị danh sách khóa học
Route::get('/', [CourseController::class, 'index'])->name('client.home');

// 2. CHI TIẾT KHÓA HỌC
Route::get('/course/{id}', [CourseController::class, 'show'])->name('client.course.detail');

// 3. ĐĂNG KÝ KHÓA HỌC (Tự xử lý thông báo lỗi trong Controller nếu chưa login)
Route::post('/course/{id}/enroll', [CourseController::class, 'enroll'])->name('client.course.enroll');

// 4. NHÓM ROUTE YÊU CẦU ĐĂNG NHẬP (USER & ADMIN)
Route::middleware('auth')->group(function () {
    // Trang "Khóa học của tôi" và Trang "Học tập"
    Route::get('/my-courses', [CourseController::class, 'myCourses'])->name('client.my_courses');
    Route::get('/course/{course_id}/learn/{lesson_id?}', [CourseController::class, 'learn'])->name('client.course.learn');
    Route::get('/course/{id}/checkout', [PaymentController::class, 'showCheckout'])
        ->name('client.checkout')
        ->middleware('auth');
    // Các route profile mặc định của Breeze
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES (Dành riêng cho Quản trị viên)
|--------------------------------------------------------------------------
*/

// Nhóm route này yêu cầu: Đã đăng nhập (auth) VÀ phải là Admin (admin middleware)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::post('courses/optimize', [AdminCourseController::class, 'optimizeImage'])
        ->name('courses.optimize');
    // Trang chủ Admin (Dashboard) - Link: /admin
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    Route::resource('courses', AdminCourseController::class);
    Route::resource('courses.lessons', AdminLessonController::class);
    Route::resource('users', AdminUserController::class);
});

require __DIR__ . '/auth.php';

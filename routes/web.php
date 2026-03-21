<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Client\CourseController;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminCourseController;
use App\Http\Controllers\Admin\AdminLessonController;
use App\Http\Controllers\Admin\AdminUserController;

use Illuminate\Support\Facades\Route;

use App\Models\Course;
use Illuminate\Support\Facades\Hash;
use App\Models\User;


// Route tạm thời để tạo dữ liệu mẫu trên Railway
Route::get('/init-data', function () {
    // 1. Tạo 1 khóa học mẫu
    Course::create([
        'title' => 'Fullstack Web với Node.js & React',
        'price' => 1200000,
        'description' => 'Học làm web từ A-Z, từ Front-end đến Back-end và Deploy.',
        'image' => 'https://images.unsplash.com/photo-1633356122544-f134324a6cee?w=800'
    ]);

    // 2. Tạo 1 tài khoản để bạn đăng nhập thử
    User::firstOrCreate(
        ['email' => 'admin@gmail.com'],
        [
            'name' => 'Admin EduHub',
            'password' => Hash::make('12345678'),
        ]
    );

    return "Đã tạo xong 1 khóa học và 1 tài khoản (admin@gmail.com / 12345678). Hãy quay lại trang chủ!";
});

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
    Route::get('/course/{id}/checkout', [CourseController::class, 'checkout'])->name('client.checkout');

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

    // Trang chủ Admin (Dashboard) - Link: /admin
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    Route::resource('courses', AdminCourseController::class);
    Route::resource('courses.lessons', AdminLessonController::class);
    Route::resource('users', AdminUserController::class);
});

require __DIR__ . '/auth.php';

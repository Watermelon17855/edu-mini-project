<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\Transaction;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function index()
    {
        // Thêm withCount('lessons') để Laravel tự đếm số bài học giúp mình
        $courses = \App\Models\Course::withCount('lessons')->latest()->get();

        // Trả về view trang chủ kèm biến $courses
        return view('client.home', compact('courses'));
    }

    public function show($id)
    {
        // Tìm khóa học theo ID, nếu không thấy sẽ báo lỗi 404
        // .with('lessons') giúp lấy luôn danh sách bài học đi kèm
        $course = Course::with('lessons')->findOrFail($id);

        return view('client.course-detail', compact('course'));
    }

    public function enroll(Request $request, $id)
    {
        // 1. Kiểm tra thủ công: Nếu CHƯA đăng nhập
        if (!Auth::check()) { // Sửa thành Auth::check()
            return back()->with('error', 'Vui lòng đăng nhập để có thể đăng ký khóa học này!');
        }

        // 2. Nếu ĐÃ đăng nhập, lấy ID người dùng
        $userId = Auth::id(); // Sửa thành Auth::id()

        // Kiểm tra xem đã đăng ký chưa
        $exists = \App\Models\Enrollment::where('user_id', $userId)
            ->where('course_id', $id)
            ->exists();

        if ($exists) {
            return back()->with('info', 'Bạn đã đăng ký khóa học này rồi!');
        }

        // Lưu vào database
        \App\Models\Enrollment::create([
            'user_id' => $userId,
            'course_id' => $id,
            'status' => 'active'
        ]);

        return back()->with('success', 'Đăng ký khóa học thành công! Bắt đầu học thôi.');
    }

    public function myCourses()
    {
        // Lấy User hiện tại
        $user = Auth::user();

        // Lấy danh sách các khóa học thông qua bảng trung gian enrollments
        // Chúng ta sẽ lấy những Course mà có liên kết với User này
        $courses = Course::whereHas('enrollments', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();

        return view('client.my-courses', compact('courses'));
    }

    public function learn($course_id, $lesson_id = null)
    {
        // 1. Kiểm tra xem User này có thực sự sở hữu khóa học này không
        $isEnrolled = Enrollment::where('user_id', Auth::id())
            ->where('course_id', $course_id)
            ->exists();

        if (!$isEnrolled) {
            // Nếu "vào nhầm" hoặc cố tình hack URL, đẩy ra trang chi tiết
            return redirect()->route('client.course.detail', $course_id)
                ->with('error', 'Bạn chưa đăng ký khóa học này!');
        }

        // 2. Lấy dữ liệu bài học
        $course = Course::with('lessons')->findOrFail($course_id);

        $currentLesson = $lesson_id
            ? Lesson::findOrFail($lesson_id)
            : $course->lessons->first();

        return view('client.learn', compact('course', 'currentLesson'));
    }

    public function checkout($courseId)
    {
        $course = Course::findOrFail($courseId);
        $user = Auth::user(); // Lấy thông tin người dùng hiện tại

        $transaction = Transaction::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'amount' => $course->price,
            'code' => 'EDU' . time(),
            'status' => 'pending'
        ]);

        // QUAN TRỌNG: Phải có 'user' ở đây
        return view('client.payment', compact('course', 'transaction', 'user'));
    }
}

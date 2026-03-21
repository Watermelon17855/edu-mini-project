<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;

class AdminLessonController extends Controller
{
    // Hiển thị danh sách bài học của một khóa học cụ thể
    public function index($courseId)
    {
        $course = Course::findOrFail($courseId);
        $lessons = $course->lessons()->orderBy('order', 'asc')->get();
        return view('admin.lessons.index', compact('course', 'lessons'));
    }

    public function create($courseId)
    {
        // Bước 1: Tìm khóa học
        $course = \App\Models\Course::findOrFail($courseId);

        // Bước 2: Tính toán số thứ tự tiếp theo (Đây chính là biến đang bị thiếu)
        $nextOrder = $course->lessons()->count() + 1;

        // Bước 3: Gửi cả 'course' và 'nextOrder' sang View
        return view('admin.lessons.create', compact('course', 'nextOrder'));
    }

    public function store(Request $request, $courseId)
    {
        // Validate mảng dữ liệu (Laravel cực mạnh phần này)
        $request->validate([
            'lessons.*.title' => 'required',
            'lessons.*.order' => 'required|numeric',
            'lessons.*.video_url' => 'required',
        ]);

        $course = \App\Models\Course::findOrFail($courseId);

        // Chạy vòng lặp để lưu từng bài
        foreach ($request->lessons as $lessonData) {
            $course->lessons()->create($lessonData);
        }

        return redirect()->route('admin.courses.lessons.index', $courseId)
            ->with('success', 'Đã thêm ' . count($request->lessons) . ' bài học mới thành công!');
    }

    // 1. Trang sửa bài học
    public function edit($courseId, $lessonId)
    {
        $course = Course::findOrFail($courseId);
        $lesson = Lesson::findOrFail($lessonId);
        return view('admin.lessons.edit', compact('course', 'lesson'));
    }

    // 2. Xử lý cập nhật
    public function update(Request $request, $courseId, $lessonId)
    {
        $request->validate(['title' => 'required', 'order' => 'required|numeric']);

        $lesson = Lesson::findOrFail($lessonId);
        $lesson->update($request->all());

        return redirect()->route('admin.courses.lessons.index', $courseId)
            ->with('success', 'Cập nhật bài học thành công!');
    }

    // 3. Xử lý xóa
    public function destroy($courseId, $lessonId)
    {
        $lesson = Lesson::findOrFail($lessonId);
        $lesson->delete();

        return redirect()->route('admin.courses.lessons.index', $courseId)
            ->with('success', 'Đã xóa bài học!');
    }
}

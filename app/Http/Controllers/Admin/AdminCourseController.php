<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AdminCourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $courses = \App\Models\Course::latest()->get();
        return view('admin.courses.index', compact('courses'));
    }

    public function create()
    {
        return view('admin.courses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'price' => 'required|numeric',
            'description' => 'nullable',
        ]);

        $data = $request->all();

        // Kiểm tra và gán vào cột 'thumbnail'
        if ($request->hasFile('image_file')) {
            // Nếu tải file từ máy
            $path = $request->file('image_file')->store('courses', 'public');
            $data['thumbnail'] = $path;
        } elseif ($request->image_url) {
            // Nếu dán link URL
            $data['thumbnail'] = $request->image_url;
        }

        \App\Models\Course::create($data);

        return redirect()->route('admin.courses.index')->with('success', 'Thêm khóa học mới thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    public function edit($id)
    {
        $course = \App\Models\Course::findOrFail($id);
        return view('admin.courses.edit', compact('course'));
    }

    // 2. Xử lý cập nhật dữ liệu
    public function update(Request $request, $id)
    {
        $course = \App\Models\Course::findOrFail($id);

        $request->validate([
            'title' => 'required|max:255',
            'price' => 'required|numeric',
        ]);

        $data = $request->all();

        // Logic xử lý ảnh thumbnail
        if ($request->hasFile('image_file')) {
            // Xóa ảnh cũ nếu là file trong storage để tránh rác server
            if ($course->thumbnail && !Str::startsWith($course->thumbnail, 'http')) {
                Storage::disk('public')->delete($course->thumbnail);
            }
            $data['thumbnail'] = $request->file('image_file')->store('courses', 'public');
        } elseif ($request->image_url) {
            $data['thumbnail'] = $request->image_url;
        }

        $course->update($data);

        return redirect()->route('admin.courses.index')->with('success', 'Cập nhật khóa học thành công!');
    }

    // 3. Xử lý xóa khóa học
    public function destroy($id)
    {
        $course = \App\Models\Course::findOrFail($id);

        // Xóa ảnh trong storage trước khi xóa record (nếu là file upload)
        if ($course->thumbnail && !Str::startsWith($course->thumbnail, 'http')) {
            Storage::disk('public')->delete($course->thumbnail);
        }

        $course->delete();
        return redirect()->route('admin.courses.index')->with('success', 'Đã xóa khóa học!');
    }
}

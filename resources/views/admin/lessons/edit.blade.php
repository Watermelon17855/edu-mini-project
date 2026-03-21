@extends('layouts.admin')
@section('title', 'Sửa bài học: ' . $lesson->title)

@section('content')
<div class="card border-0 shadow-sm rounded-4 p-4">
    <form action="{{ route('admin.courses.lessons.update', [$course->id, $lesson->id]) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-8 mb-3">
                <label class="form-label fw-bold">Tiêu đề bài học</label>
                <input type="text" name="title"
                    value="{{ old('title', $lesson->title) }}"
                    class="form-control rounded-3" required>
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label fw-bold">Thứ tự</label>
                <input type="number" name="order"
                    value="{{ old('order', $lesson->order) }}"
                    class="form-control rounded-3" required>
            </div>

            <div class="col-12 mb-3">
                <label class="form-label fw-bold">Video ID (Youtube)</label>
                <div class="input-group">
                    <span class="input-group-text bg-light text-secondary border-end-0">youtube.com/watch?v=</span>
                    <input type="text" name="video_url"
                        value="{{ old('video_url', $lesson->video_url) }}"
                        class="form-control rounded-3 border-start-0" required>
                </div>
            </div>

            <div class="col-12 mb-4">
                <label class="form-label fw-bold">Nội dung bài học</label>
                <textarea name="content" rows="4" class="form-control rounded-3">{{ old('content', $lesson->content) }}</textarea>
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-primary px-5 rounded-pill fw-bold">CẬP NHẬT</button>
                <a href="{{ route('admin.courses.lessons.index', $course->id) }}" class="btn btn-light px-4 rounded-pill border">Hủy</a>
            </div>
        </div>
    </form>
</div>
@endsection
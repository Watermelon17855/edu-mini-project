@extends('layouts.admin')
@section('title', 'Thêm bài học mới cho: ' . $course->title)

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.courses.lessons.index', $course->id) }}" class="text-decoration-none">
        <i class="bi bi-arrow-left"></i> Quay lại danh sách bài học
    </a>
</div>

<div class="card border-0 shadow-sm rounded-4 p-4">
    <form action="{{ route('admin.courses.lessons.store', $course->id) }}" method="POST">
        @csrf

        <div id="lessons-wrapper">
            <div class="lesson-item border-bottom mb-4 pb-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    {{-- Sửa: Hiển thị đúng số thứ tự tiếp theo của khóa học --}}
                    <h5 class="fw-bold mb-0 text-primary">Bài học #{{ $nextOrder }}</h5>
                </div>
                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label class="form-label fw-bold">Tiêu đề bài học</label>
                        <input type="text" name="lessons[0][title]" class="form-control rounded-3" placeholder="Ví dụ: Bài 1 - Giới thiệu về Laravel" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Thứ tự hiển thị</label>
                        <input type="number" name="lessons[0][order]" class="form-control rounded-3" value="{{ $nextOrder }}" required>
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label fw-bold">Video ID (Youtube)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-secondary border-end-0">youtube.com/watch?v=</span>
                            <input type="text" name="lessons[0][video_url]" class="form-control rounded-3 border-start-0" placeholder="ví dụ: dQw4w9WgXcQ" required>
                        </div>
                    </div>

                    <div class="col-12 mb-4">
                        <label class="form-label fw-bold">Mô tả/Nội dung bài học</label>
                        <textarea name="lessons[0][content]" rows="4" class="form-control rounded-3" placeholder="Nhập nội dung hướng dẫn cho học viên..."></textarea>
                    </div>
                </div>
            </div>
        </div>

        <button type="button" id="add-more-lesson" class="btn btn-outline-secondary rounded-pill px-4 mb-3">
            <i class="bi bi-plus-circle me-2"></i> Thêm bài học khác
        </button>

        <div class="col-12">
            <hr>
            <button type="submit" class="btn btn-primary px-5 rounded-pill fw-bold">LƯU TẤT CẢ BÀI HỌC</button>
        </div>
    </form>
</div>

<script>
    // Đợi trang load xong hoàn toàn mới chạy script
    document.addEventListener('DOMContentLoaded', function() {
        // Lấy số thứ tự khởi đầu từ PHP (dùng dấu nháy để an toàn)
        let startOrder = parseInt("{{ $nextOrder ?? 1 }}");
        let lessonIndex = 1;

        const addBtn = document.getElementById('add-more-lesson');
        const wrapper = document.getElementById('lessons-wrapper');

        // Kiểm tra xem nút có tồn tại không trước khi gắn sự kiện
        if (addBtn) {
            addBtn.onclick = function(e) {
                e.preventDefault(); // Chặn trang bị reload khi bấm nút

                const newItem = document.createElement('div');
                newItem.className = 'lesson-item border-bottom mb-4 pb-3';

                let currentDisplayOrder = startOrder + lessonIndex;

                // Lưu ý: Dùng dấu backtick ( ` ) bao quanh toàn bộ chuỗi HTML
                newItem.innerHTML = `
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0 text-primary">Bài học #${currentDisplayOrder}</h5>
                        <button type="button" class="btn btn-sm btn-outline-danger border-0 remove-lesson">Xóa bài này</button>
                    </div>
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label class="form-label fw-bold">Tiêu đề bài học</label>
                            <input type="text" name="lessons[${lessonIndex}][title]" class="form-control rounded-3" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Thứ tự hiển thị</label>
                            <input type="number" name="lessons[${lessonIndex}][order]" class="form-control rounded-3" value="${currentDisplayOrder}" required>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label fw-bold">Video ID (Youtube)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-secondary border-end-0">youtube.com/watch?v=</span>
                                <input type="text" name="lessons[${lessonIndex}][video_url]" class="form-control rounded-3 border-start-0" required>
                            </div>
                        </div>
                        <div class="col-12 mb-4">
                            <label class="form-label fw-bold">Mô tả/Nội dung bài học</label>
                            <textarea name="lessons[${lessonIndex}][content]" rows="4" class="form-control rounded-3"></textarea>
                        </div>
                    </div>
                `;

                wrapper.appendChild(newItem);
                lessonIndex++;

                // Gắn sự kiện xóa cho nút vừa mới tạo
                newItem.querySelector('.remove-lesson').onclick = function() {
                    newItem.remove();
                };
            };
        }
    });
</script>
@endsection
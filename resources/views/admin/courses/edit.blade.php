@extends('layouts.admin')
@section('title', 'Sửa khóa học: ' . $course->title)

@section('content')
<div class="card border-0 shadow-sm rounded-4 p-4">
    <form action="{{ route('admin.courses.update', $course->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT') {{-- Bắt buộc phải có khi dùng Route Resource để Update --}}

        <div class="row">
            <div class="col-md-8 mb-3">
                <label class="form-label fw-bold">Tên khóa học</label>
                <input type="text" name="title" value="{{ old('title', $course->title) }}" class="form-control rounded-3" required>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label fw-bold">Giá tiền (VNĐ)</label>
                <input type="number" name="price" value="{{ old('price', $course->price) }}" class="form-control rounded-3" required>
            </div>

            <div class="col-12 mb-3">
                <label class="form-label fw-bold d-block">Ảnh đại diện hiện tại</label>
                <img src="{{ Str::startsWith($course->thumbnail, 'http') ? $course->thumbnail : asset('storage/' . $course->thumbnail) }}"
                    class="rounded-3 mb-3 border" style="width: 150px; height: 100px; object-fit: cover;">

                <div class="d-flex gap-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="image_type" id="type_file" value="file" checked>
                        <label class="form-check-label" for="type_file">Tải file mới</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="image_type" id="type_url" value="url">
                        <label class="form-check-label" for="type_url">Dán link mới</label>
                    </div>
                </div>

                <div id="input_file">
                    <input type="file" name="image_file" class="form-control rounded-3">
                </div>
                <div id="input_url" style="display: none;">
                    <input type="text" name="image_url" value="{{ Str::startsWith($course->thumbnail, 'http') ? $course->thumbnail : '' }}" class="form-control rounded-3" placeholder="Nhập link ảnh mới...">
                </div>
            </div>

            <div class="col-12 mb-4">
                <label class="form-label fw-bold">Mô tả khóa học</label>
                <textarea name="description" rows="5" class="form-control rounded-3">{{ old('description', $course->description) }}</textarea>
            </div>

            <div class="col-12">
                <hr>
                <button type="submit" class="btn btn-primary px-5 rounded-pill fw-bold">CẬP NHẬT KHÓA HỌC</button>
                <a href="{{ route('admin.courses.index') }}" class="btn btn-light px-4 rounded-pill border">Hủy</a>
            </div>
        </div>
    </form>
</div>

<script>
    document.querySelectorAll('input[name="image_type"]').forEach(radio => {
        radio.addEventListener('change', function() {
            document.getElementById('input_file').style.display = (this.value === 'file') ? 'block' : 'none';
            document.getElementById('input_url').style.display = (this.value === 'url') ? 'block' : 'none';
        });
    });
</script>
@endsection
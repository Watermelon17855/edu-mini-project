@extends('layouts.admin')
@section('title', 'Sửa khóa học: ' . $course->title)

@section('content')
@php
// Kiểm tra xem ảnh hiện tại là dạng link hay dạng file
$isUrl = Str::startsWith($course->thumbnail, 'http');
@endphp

<div class="card border-0 shadow-sm rounded-4 p-4">
    <form action="{{ route('admin.courses.update', $course->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

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
                <img src="{{ $isUrl ? $course->thumbnail : asset('storage/' . $course->thumbnail) }}"
                    class="rounded-3 mb-3 border" style="width: 150px; height: 100px; object-fit: cover;">

                <div class="d-flex gap-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="image_type" id="type_file" value="file" {{ !$isUrl ? 'checked' : '' }}>
                        <label class="form-check-label" for="type_file">Tải file mới</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="image_type" id="type_url" value="url" {{ $isUrl ? 'checked' : '' }}>
                        <label class="form-check-label" for="type_url">Dán link mới</label>
                    </div>
                </div>

                <div id="input_file" style="display: {{ !$isUrl ? 'block' : 'none' }};">
                    <input type="file" name="image_file" class="form-control rounded-3">
                </div>

                <div id="input_url" style="display: {{ $isUrl ? 'block' : 'none' }};">
                    {{-- THÊM INPUT GROUP VÀ NÚT TỐI ƯU --}}
                    <div class="input-group">
                        <input type="text" id="image_url_input" name="image_url" value="{{ $isUrl ? $course->thumbnail : '' }}" class="form-control rounded-start-3" placeholder="Nhập link hoặc dán Base64...">
                        <button class="btn btn-outline-primary fw-bold" type="button" id="btn-optimize">
                            <i class="bi bi-magic"></i> Tối ưu
                        </button>
                    </div>

                    <div class="form-text text-warning mt-2" id="optimize-msg">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i>
                        Lưu ý: Link ảnh không được vượt quá 255 ký tự. Nhấn "Tối ưu" nếu bạn dán chuỗi Base64 dài.
                    </div>
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
    // Logic ẩn hiện cũ của bạn
    document.querySelectorAll('input[name="image_type"]').forEach(radio => {
        radio.addEventListener('change', function() {
            document.getElementById('input_file').style.display = (this.value === 'file') ? 'block' : 'none';
            document.getElementById('input_url').style.display = (this.value === 'url') ? 'block' : 'none';
        });
    });

    // THÊM LOGIC XỬ LÝ NÚT TỐI ƯU
    document.getElementById('btn-optimize').addEventListener('click', function() {
        const urlInput = document.getElementById('image_url_input');
        const url = urlInput.value;
        const btn = this;
        const msg = document.getElementById('optimize-msg');

        if (!url) return alert('Vui lòng dán link trước!');

        // Chỉ tối ưu nếu link dài hoặc là Base64 để tránh phí tài nguyên
        if (url.length < 50 && !url.startsWith('data:image')) {
            return alert('Link này đã đủ ngắn, không cần tối ưu đâu bạn ơi!');
        }

        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';

        fetch('{{ route("admin.courses.optimize") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    url: url
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    urlInput.value = data.short_path;
                    msg.innerHTML = '<span class="text-success fw-bold"><i class="bi bi-check-circle-fill"></i> Đã tối ưu và rút gọn thành công!</span>';
                } else {
                    alert(data.msg || 'Có lỗi xảy ra!');
                }
            })
            .catch(err => alert('Lỗi kết nối server!'))
            .finally(() => {
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-magic"></i> Tối ưu';
            });
    });
</script>
@endsection
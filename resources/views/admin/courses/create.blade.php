@extends('layouts.admin')
@section('title', 'Thêm khóa học mới')

@section('content')
<div class="card border-0 shadow-sm rounded-4 p-4">
    <form action="{{ route('admin.courses.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-8 mb-3">
                <label class="form-label fw-bold">Tên khóa học</label>
                <input type="text" name="title" class="form-control rounded-3" placeholder="Ví dụ: Lập trình PHP cơ bản" required>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label fw-bold">Giá tiền (VNĐ)</label>
                <input type="number" name="price" class="form-control rounded-3" placeholder="Ví dụ: 500000" required>
            </div>

            <div class="col-12 mb-3">
                <label class="form-label fw-bold">Ảnh đại diện khóa học</label>
                <div class="d-flex gap-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="image_type" id="type_file" value="file" checked>
                        <label class="form-check-label" for="type_file">Tải lên từ máy</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="image_type" id="type_url" value="url">
                        <label class="form-check-label" for="type_url">Dùng link URL</label>
                    </div>
                </div>

                <div id="input_file">
                    <input type="file" name="image_file" class="form-control rounded-3">
                </div>
                <div id="input_url" style="display: none;">
                    <input type="text" name="image_url" class="form-control rounded-3" placeholder="Nhập link ảnh (http://...)">
                </div>
            </div>

            <div class="col-12 mb-4">
                <label class="form-label fw-bold">Mô tả khóa học</label>
                <textarea name="description" rows="5" class="form-control rounded-3" placeholder="Giới thiệu sơ qua về nội dung khóa học..."></textarea>
            </div>

            <div class="col-12">
                <hr>
                <button type="submit" class="btn btn-primary px-5 rounded-pill fw-bold">LƯU KHÓA HỌC</button>
                <a href="{{ route('admin.courses.index') }}" class="btn btn-light px-4 rounded-pill">Hủy bỏ</a>
            </div>
        </div>
    </form>
</div>

<script>
    document.querySelectorAll('input[name="image_type"]').forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'file') {
                document.getElementById('input_file').style.display = 'block';
                document.getElementById('input_url').style.display = 'none';
            } else {
                document.getElementById('input_file').style.display = 'none';
                document.getElementById('input_url').style.display = 'block';
            }
        });
    });
</script>
@endsection
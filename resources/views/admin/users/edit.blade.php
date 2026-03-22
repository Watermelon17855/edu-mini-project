@extends('layouts.admin')
@section('title', 'Thông tin của: ' . $user->name)

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.users.index') }}" class="text-decoration-none">
        <i class="bi bi-arrow-left"></i> Quay lại danh sách
    </a>
</div>

{{-- 1. XÓA max-width ĐỂ CARD RỘNG TOÀN MÀN HÌNH --}}
<div class="card border-0 shadow-sm rounded-4 p-4">
    <h5 class="fw-bold mb-4">Phân quyền người dùng</h5>

    <div class="d-flex align-items-center mb-4">
        <div class="avatar-md bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
            {{ strtoupper(substr($user->name, 0, 1)) }}
        </div>
        <div>
            <h6 class="mb-0 fw-bold">{{ $user->name }}</h6>
            <small class="text-muted">{{ $user->email }}</small>
        </div>
    </div>

    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="form-label fw-bold">Chọn vai trò mới</label>
            <select name="role" class="form-select rounded-3 p-2">
                <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User (Học viên)</option>
                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin (Quản trị viên)</option>
            </select>
            <div class="form-text mt-2 text-warning">
                <i class="bi bi-exclamation-triangle"></i> Lưu ý: Quyền Admin có thể xóa và sửa dữ liệu hệ thống.
            </div>
        </div>

        <button type="submit" class="btn btn-primary px-5 rounded-pill fw-bold">XÁC NHẬN THAY ĐỔI</button>
    </form> {{-- ĐÓNG FORM TẠI ĐÂY ĐỂ TÁCH BIỆT VỚI BẢNG DƯỚI --}}
</div>

{{-- 2. CARD KHÓA HỌC NẰM RIÊNG ĐỂ TỰ DO DÀN TRẢI ĐẾN HẾT BÊN PHẢI --}}
<div class="card border-0 shadow-sm rounded-4 p-4 mt-4">
    <h5 class="fw-bold mb-3 text-dark">
        <i class="bi bi-mortarboard-fill me-2 text-primary"></i>Khóa học đã mua
    </h5>

    <div class="table-responsive">
        <table class="table table-hover align-middle border-top">
            <thead class="table-light">
                <tr>
                    <th>Khóa học</th>
                    <th>Ngày mua</th>
                    <th class="text-center">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($user->courses as $course)
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <img src="{{ \Illuminate\Support\Str::startsWith($course->thumbnail, 'http') ? $course->thumbnail : asset('storage/' . $course->thumbnail) }}"
                                class="rounded me-3"
                                style="width: 50px; height: 35px; object-fit: cover;"
                                alt="{{ $course->title }}"> <span class="fw-semibold">{{ $course->title }}</span>
                        </div>
                    </td>
                    <td class="small text-muted">
                        {{ $course->pivot->created_at->format('d/m/Y') }}
                    </td>
                    <td class="text-center">
                        <div class="dropdown">
                            {{-- Nút dấu chấm than (Info icon) --}}
                            <button class="btn btn-light btn-sm rounded-circle shadow-sm border" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="width: 32px; height: 32px;">
                                <i class="bi bi-info-lg text-primary"></i>
                            </button>

                            {{-- Menu hiện ra khi nhấn vào --}}
                            <ul class="dropdown-menu shadow border-0 mt-2">
                                <li>
                                    <a class="dropdown-item py-2" href="{{ route('admin.courses.edit', $course->id) }}">
                                        <i class="bi bi-eye me-2 text-secondary"></i> Xem chi tiết khóa học
                                    </a>
                                </li>
                                <li>
                                    {{-- Route này dựa theo web.php bạn gửi lúc nãy (AdminCourseLessonController) --}}
                                    <a class="dropdown-item py-2" href="{{ route('admin.courses.lessons.index', $course->id) }}">
                                        <i class="bi bi-play-circle me-2 text-primary"></i> Xem danh sách bài học
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="text-center py-4 text-muted">
                        <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                        Người dùng này chưa mua khóa học nào.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
@extends('layouts.admin')
@section('title', 'Thay đổi vai trò: ' . $user->name)

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.users.index') }}" class="text-decoration-none">
        <i class="bi bi-arrow-left"></i> Quay lại danh sách
    </a>
</div>

<div class="card border-0 shadow-sm rounded-4 p-4" style="max-width: 500px;">
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

        <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold">XÁC NHẬN THAY ĐỔI</button>
    </form>
</div>
@endsection
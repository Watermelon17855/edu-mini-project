@extends('layouts.admin')
@section('title', 'Quản lý người dùng')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold">Danh sách học viên & Admin</h3>
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4">Tên người dùng</th>
                    <th>Email</th>
                    <th>Vai trò</th>
                    <th>Ngày tham gia</th>
                    <th class="text-center">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td class="ps-4">
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm me-3 bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <span class="fw-bold text-dark">{{ $user->name }}</span>
                        </div>
                    </td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <span class="badge {{ $user->role == 'admin' ? 'bg-danger' : 'bg-info' }} rounded-pill px-3">
                            {{ strtoupper($user->role) }}
                        </span>
                    </td>
                    <td>{{ $user->created_at->format('d/m/Y') }}</td>
                    <td class="text-center">
                        <div class="btn-group">
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-outline-primary border-0">
                                <i class="bi bi-shield-lock fs-5"></i>
                            </a>

                            {{-- CHỈ HIỆN NÚT XÓA NẾU ĐÓ KHÔNG PHẢI LÀ CHÍNH BẠN --}}
                            @if($user->id !== Auth::id())
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Xóa người dùng này?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger border-0">
                                    <i class="bi bi-trash fs-5"></i>
                                </button>
                            </form>
                            @else
                            {{-- Nếu là chính mình, hiện nút xóa bị mờ đi --}}
                            <button class="btn btn-sm btn-outline-secondary border-0" disabled title="Bạn không thể tự xóa chính mình">
                                <i class="bi bi-trash fs-5"></i>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
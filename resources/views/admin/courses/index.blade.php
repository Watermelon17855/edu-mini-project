@extends('layouts.admin')
@section('title', 'Danh sách khóa học')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold">Quản lý khóa học</h3>
    <a href="{{ route('admin.courses.create') }}" class="btn btn-primary rounded-pill px-4">
        <i class="bi bi-plus-lg me-2"></i> Thêm khóa học mới
    </a>
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4" style="width: 80px;">Hình ảnh</th>
                    <th>Tên khóa học</th>
                    <th>Giá tiền</th>
                    <th>Ngày tạo</th>
                    <th class="text-center">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @foreach($courses as $course)
                <tr>
                    <td class="ps-4">
                        <img src="{{ \Illuminate\Support\Str::startsWith($course->thumbnail, 'http') ? $course->thumbnail : asset('storage/' . $course->thumbnail) }}"
                            class="rounded-3" style="width: 60px; height: 40px; object-fit: cover;">
                    </td>
                    <td>
                        <div class="fw-bold text-dark">{{ $course->title }}</div>
                        <small class="text-muted">{{ Str::limit($course->description, 50) }}</small>
                    </td>
                    <td><span class="badge bg-light text-success fs-6">{{ number_format($course->price) }}đ</span></td>
                    <td>{{ $course->created_at->format('d/m/Y') }}</td>
                    <td class="text-center">
                        <div class="btn-group">
                            <a href="{{ route('admin.courses.lessons.index', $course->id) }}"
                                class="btn btn-sm btn-outline-success border-0"
                                title="Danh sách bài học">
                                <i class="bi bi-collection-play fs-5"></i>
                            </a>

                            <a href="{{ route('admin.courses.edit', $course->id) }}"
                                class="btn btn-sm btn-outline-primary border-0"
                                title="Sửa khóa học">
                                <i class="bi bi-pencil-square fs-5"></i>
                            </a>

                            <form action="{{ route('admin.courses.destroy', $course->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa khóa học này?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger border-0" title="Xóa khóa học">
                                    <i class="bi bi-trash fs-5"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
@extends('layouts.admin')
@section('title', 'Bài học của khóa: ' . $course->title)

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.courses.index') }}" class="text-decoration-none">
        <i class="bi bi-arrow-left"></i> Quay lại danh sách khóa học
    </a>
</div>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-0">Bài học: {{ $course->title }}</h3>
        <p class="text-muted small">Quản lý nội dung video và thứ tự bài học</p>
    </div>
    <a href="{{ route('admin.courses.lessons.create', $course->id) }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
        <i class="bi bi-plus-lg me-2"></i> Thêm bài học mới
    </a>
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4" style="width: 100px;">Thứ tự</th>
                    <th>Tiêu đề bài học</th>
                    <th>Video ID</th>
                    <th class="text-center" style="width: 150px;">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($lessons as $lesson)
                <tr>
                    <td class="ps-4">
                        <span class="badge rounded-pill bg-secondary bg-opacity-10 text-secondary px-3">
                            #{{ $lesson->order }}
                        </span>
                    </td>
                    <td>
                        <div class="fw-bold text-dark">{{ $lesson->title }}</div>
                        @if($lesson->content)
                        <small class="text-muted text-truncate d-block" style="max-width: 300px;">
                            {{ Str::limit(strip_tags($lesson->content), 50) }}
                        </small>
                        @endif
                    </td>
                    <td>
                        <span class="badge bg-light text-dark border fw-normal">
                            <i class="bi bi-youtube text-danger me-1"></i> {{ $lesson->video_url }}
                        </span>
                    </td>
                    <td class="text-center">
                        <div class="btn-group">
                            <a href="{{ route('admin.courses.lessons.edit', [$course->id, $lesson->id]) }}"
                                class="btn btn-sm btn-outline-primary border-0"
                                title="Sửa bài học">
                                <i class="bi bi-pencil-square fs-5"></i>
                            </a>

                            <form action="{{ route('admin.courses.lessons.destroy', [$course->id, $lesson->id]) }}"
                                method="POST"
                                onsubmit="return confirm('Bạn có chắc muốn xóa bài học này không? Hành động này không thể hoàn tác!')"
                                class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger border-0" title="Xóa bài học">
                                    <i class="bi bi-trash fs-5"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-5 text-muted">
                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                        Chưa có bài học nào. Hãy thêm bài học đầu tiên cho khóa học này!
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
@extends('layouts.client')

@section('title', $course->title)

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-8">
            <div class="mb-4">
                <a href="{{ route('client.home') }}" class="btn btn-light shadow-sm rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 45px; height: 45px;" title="Quay lại trang chủ">
                    <i class="bi bi-arrow-left fs-4 text-primary"></i>
                </a>
            </div>

            {{-- 1. FIX LỖI ẢNH: Thêm logic kiểm tra link http hoặc storage --}}
            <img src="{{ \Illuminate\Support\Str::startsWith($course->thumbnail, 'http') ? $course->thumbnail : asset('storage/' . $course->thumbnail) }}"
                class="img-fluid rounded shadow-sm mb-4"
                alt="{{ $course->title }}"
                style="width: 100%; max-height: 400px; object-fit: cover;">

            <h1 class="fw-bold">{{ $course->title }}</h1>
            <p class="lead text-secondary">{{ $course->description }}</p>

            <hr>
            <h4 class="fw-bold mb-3">Nội dung chi tiết</h4>
            <div class="bg-white p-4 rounded shadow-sm">
                {!! nl2br(e($course->description)) !!}
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0 sticky-top" style="top: 20px;">
                <div class="card-body">
                    <h3 class="text-danger fw-bold mb-3">{{ number_format($course->price) }}đ</h3>

                    <a href="{{ route('client.checkout', $course->id) }}" class="btn btn-primary w-100 py-2 fw-bold mb-3">
                        ĐĂNG KÝ HỌC NGAY
                    </a>

                    <h5 class="fw-bold mb-3">Danh sách bài học</h5>
                    <div class="list-group list-group-flush">
                        @forelse($course->lessons as $index => $lesson)
                        <div class="list-group-item d-flex align-items-center gap-2 border-0 px-0">
                            <i class="bi bi-play-circle-fill text-primary"></i>
                            <span>Bài {{ $index + 1 }}: {{ $lesson->title }}</span>
                        </div>
                        @empty
                        <p class="text-muted small">Khóa học này đang cập nhật bài học...</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
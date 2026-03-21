@extends('layouts.client')

@section('title', 'Trang chủ - Danh sách khóa học')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-11 col-xl-11">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold">Khóa học mới nhất</h2>
            </div>

            <div class="row">
                @foreach($courses as $course)
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card h-100 course-card shadow-sm border-0 rounded-4 overflow-hidden">

                        <div class="ratio ratio-21x9">
                            <img src="{{ \Illuminate\Support\Str::startsWith($course->thumbnail, 'http') ? $course->thumbnail : asset('storage/' . $course->thumbnail) }}"
                                class="card-img-top"
                                alt="{{ $course->title }}"
                                style="object-fit: cover;">
                        </div>

                        <div class="card-body d-flex flex-column p-3">
                            <h6 class="card-title fw-bold text-dark mb-1" style="min-height: 40px; font-size: 0.95rem;">
                                {{ $course->title }}
                            </h6>

                            <p class="card-text text-secondary mb-3" style="font-size: 0.8rem; line-height: 1.4;">
                                {{ Str::limit($course->description, 55) }}
                            </p>

                            <div class="d-flex align-items-center mt-auto justify-content-between">
                                @if($course->lessons_count > 0)
                                <span class="fw-bold text-danger">{{ number_format($course->price) }}đ</span>
                                <a href="{{ route('client.course.detail', $course->id) }}" class="btn btn-primary ...">Chi tiết</a>
                                @else
                                <span class="badge bg-secondary py-2 px-3 rounded-pill">Đang cập nhật</span>
                                <button class="btn btn-light btn-sm ... text-muted" disabled>Sắp ra mắt</button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

        </div>
    </div>
</div>
@endsection
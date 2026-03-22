@extends('layouts.client')

@section('title', 'Khóa học của tôi')

@section('content')
<div class="container">
    <h2 class="fw-bold mb-4">Khóa học bạn đã đăng ký</h2>

    @if($courses->isEmpty())
    <div class="text-center py-5">
        <p class="text-muted">Bạn chưa đăng ký khóa học nào.</p>
        <a href="{{ route('client.home') }}" class="btn btn-primary rounded-pill">Khám phá ngay</a>
    </div>
    @else
    <div class="row">
        @foreach($courses as $course)
        <div class="col-md-4 mb-4">
            <div class="card h-100 course-card shadow-sm border-0">
                <img src="{{ \Illuminate\Support\Str::startsWith($course->thumbnail, 'http') ? $course->thumbnail : asset('storage/' . $course->thumbnail) }}"
                    class="card-img-top"
                    alt="{{ $course->title }}"
                    style="height: 180px; object-fit: cover;">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title fw-bold">{{ $course->title }}</h5>
                    <div class="mt-auto">
                        <a href="{{ route('client.course.learn', $course->id) }}" class="btn btn-primary w-100 rounded-pill fw-bold">VÀO HỌC NGAY</a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection
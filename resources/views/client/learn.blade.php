@extends('layouts.client')
@section('title', 'Đang học: ' . ($currentLesson->title ?? 'Nội dung đang cập nhật'))

@section('content')
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                @if($currentLesson->video_url)
                <div class="ratio ratio-16x9">
                    <iframe src="https://www.youtube.com/embed/{{ $currentLesson->video_url }}" allowfullscreen></iframe>
                </div>
                @endif
                <div class="card-body p-4">
                    <h3 class="fw-bold">{{ $currentLesson->title }}</h3>
                    <hr>
                    <div class="mt-3">
                        {!! $currentLesson->content !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="mb-0 fw-bold">Nội dung khóa học</h5>
                </div>
                <div class="list-group list-group-flush px-2 pb-3">
                    @foreach($course->lessons as $lesson)
                    <a href="{{ route('client.course.learn', [$course->id, $lesson->id]) }}"
                        class="list-group-item list-group-item-action border-0 rounded-3 mb-1 {{ $currentLesson->id == $lesson->id ? 'active' : '' }}">
                        <i class="bi {{ $currentLesson->id == $lesson->id ? 'bi-play-circle-fill' : 'bi-play-circle' }} me-2"></i>
                        {{ $lesson->title }}
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
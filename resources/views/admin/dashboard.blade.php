@extends('layouts.admin')
@section('title', 'Bảng thống kê')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-primary text-white">
            <div class="d-flex align-items-center">
                <div class="fs-1 me-3"><i class="bi bi-people"></i></div>
                <div>
                    <h6 class="mb-0">Tổng học viên</h6>
                    <h2 class="fw-bold mb-0">{{ $stats['total_users'] }}</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-success text-white">
            <div class="d-flex align-items-center">
                <div class="fs-1 me-3"><i class="bi bi-journal-bookmark"></i></div>
                <div>
                    <h6 class="mb-0">Tổng khóa học</h6>
                    <h2 class="fw-bold mb-0">{{ $stats['total_courses'] }}</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-warning text-dark">
            <div class="d-flex align-items-center">
                <div class="fs-1 me-3"><i class="bi bi-cart-check"></i></div>
                <div>
                    <h6 class="mb-0">Lượt đăng ký học</h6>
                    <h2 class="fw-bold mb-0">{{ $stats['total_enrollments'] }}</h2>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white py-3 border-0">
        <h5 class="mb-0 fw-bold">Hoạt động đăng ký gần đây</h5>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4">Học viên</th>
                    <th>Khóa học</th>
                    <th>Ngày đăng ký</th>
                </tr>
            </thead>
            <tbody>
                @foreach($stats['recent_enrollments'] as $en)
                <tr>
                    <td class="ps-4 fw-bold text-primary">{{ $en->user->name }}</td>
                    <td>{{ $en->course->title }}</td>
                    <td>{{ $en->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
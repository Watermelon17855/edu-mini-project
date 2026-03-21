@extends('layouts.client')
@section('title', 'Thanh toán khóa học')


@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<div class="container py-5">
    <div class="row g-4">
        <div class="col-md-7">
            <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                <h4 class="fw-bold mb-4"><i class="bi bi-person-check me-2 text-primary"></i>Thông tin đăng ký</h4>

                <div class="mb-3">
                    <label class="form-label fw-bold text-secondary">Họ và tên</label>
                    <input type="text" class="form-control form-control-lg rounded-3 bg-light" value="{{ $user->name }}" readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold text-secondary">Email nhận khóa học</label>
                    <input type="email" class="form-control form-control-lg rounded-3 bg-light" value="{{ $user->email }}" readonly>
                </div>

                <div class="mt-4 p-3 rounded-3 border-start border-4 border-primary bg-primary bg-opacity-10">
                    <h6 class="fw-bold mb-1">Khóa học đăng ký:</h6>
                    <p class="mb-0 text-primary fw-bold fs-5">{{ $course->title }}</p>
                    <p class="mb-0 mt-2">Tổng tiền: <span class="text-danger fw-bold fs-4">{{ number_format($course->price) }}đ</span></p>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100">
                <h4 class="fw-bold mb-4">Quét mã VietQR</h4>

                <div class="mb-3 qr-wrapper">
                    {{-- Link QR lấy theo cấu trúc SePay bạn dùng bên React --}}
                    <img src="https://qr.sepay.vn/img?acc=0388100173&bank=VPBank&amount={{ $transaction->amount }}&des={{ $transaction->code }}"
                        alt="QR Thanh toán" class="img-fluid rounded-3 shadow-sm border p-2 sepay-qr" style="max-width: 280px;">
                </div>

                <div class="bg-light p-3 rounded-3 mb-3 text-start border-start border-4 border-danger">
                    <p class="mb-1 small text-muted">Nội dung chuyển khoản bắt buộc:</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fs-5 fw-bold text-danger">{{ $transaction->code }}</span>

                    </div>
                </div>

                <div class="d-flex align-items-center justify-content-center text-muted small">
                    <div class="spinner-border spinner-border-sm me-2 text-primary pulse-animation" role="status"></div>
                    Đang chờ hệ thống xác nhận thanh toán...
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Hiệu ứng giống React của bạn */
    .sepay-qr {
        mix-blend-mode: multiply;
        transition: transform 0.5s ease;
    }

    .qr-wrapper:hover .sepay-qr {
        transform: scale(1.05);
        /* Phóng to khi hover */
    }

    .pulse-animation {
        animation: pulse 1.5s infinite;
    }

    @keyframes pulse {
        0% {
            opacity: 1;
        }

        50% {
            opacity: 0.4;
        }

        100% {
            opacity: 1;
        }
    }
</style>

{{-- Script tự động kiểm tra thanh toán --}}
<script>
    const checkPayment = setInterval(function() {
        fetch(`/api/check-transaction/{{ $transaction->id }}`)
            .then(res => res.json())
            .then(data => {
                if (data.status === 'completed') {
                    // 1. Dừng việc kiểm tra ngay lập tức
                    clearInterval(checkPayment);

                    // 2. Hiện bảng thông báo "Xịn"
                    Swal.fire({
                        title: 'Thanh toán thành công!',
                        text: 'Cảm ơn bạn! Hệ thống đã kích hoạt khóa học.',
                        icon: 'success',
                        showConfirmButton: true,
                        confirmButtonText: 'Về trang chủ ngay',
                        confirmButtonColor: '#0d6efd',
                        allowOutsideClick: false // Bắt buộc khách phải bấm nút mới chuyển trang
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // 3. Điều hướng về trang chủ
                            // Đồng thời đính kèm một thông báo nhỏ để hiện ở trang chủ
                            window.location.href = "{{ route('client.home') }}?payment_success=true";
                        }
                    });
                }
            })
            .catch(err => console.log('Đang kết nối máy chủ...'));
    }, 3000); // 3 giây check 1 lần cho nhanh
</script>
@endsection
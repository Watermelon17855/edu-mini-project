<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Kiểm tra: Nếu đã đăng nhập VÀ có quyền là 'admin'
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request); // Cho phép đi tiếp vào trang Admin
        }

        // Nếu không phải Admin, "đá" về trang chủ kèm thông báo lỗi
        return redirect('/')->with('error', 'Dừng lại! Bạn không có quyền truy cập khu vực này.');
    }
}

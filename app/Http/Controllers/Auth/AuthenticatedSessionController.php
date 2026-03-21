<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        // KIỂM TRA ROLE SAU KHI ĐĂNG NHẬP THÀNH CÔNG
        if ($request->user()->role === 'admin') {
            // Nếu là Admin, chuyển hướng tới trang Dashboard của Admin
            return redirect()->intended(route('admin.dashboard'));
        }

        // Nếu là User bình thường, chuyển hướng về trang chủ
        return redirect()->intended(route('client.home'));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}

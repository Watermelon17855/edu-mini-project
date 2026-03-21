<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .sidebar {
            min-width: 250px;
            max-width: 250px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: all 0.3s;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, .8);
            border-radius: 8px;
            margin: 4px 10px;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: rgba(255, 255, 255, .1);
            color: #fff;
        }

        .main-content {
            flex: 1;
        }
    </style>
</head>

<body class="bg-light d-flex">
    <div class="sidebar bg-dark shadow">
        <div class="flex-grow-1">
            <div class="p-4 text-white text-center">
                <h4 class="fw-bold">EDU-ADMIN</h4>
                <hr>
            </div>
            <nav class="nav flex-column px-2">
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active bg-primary' : '' }}" href="{{ route('admin.dashboard') }}">
                    <i class="bi bi-speedometer2 me-2"></i> Thống kê
                </a>
                <a class="nav-link {{ request()->routeIs('admin.courses.*') ? 'active bg-primary' : '' }}"
                    href="{{ route('admin.courses.index') }}">
                    <i class="bi bi-book me-2"></i> Quản lý khóa học
                </a>
                <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active bg-primary' : '' }}" href="{{ route('admin.users.index') }}">
                    <i class="bi bi-people me-2"></i> Quản lý người dùng
                </a>
            </nav>
        </div>

        <div class="mt-auto pb-4 border-top pt-3">
            <a class="nav-link text-info px-4 mb-2" href="{{ route('client.home') }}">
                <i class="bi bi-house me-2"></i> Về trang chủ
            </a>
            <form action="{{ route('logout') }}" method="POST" class="px-4">
                @csrf
                <button class="nav-link text-danger border-0 bg-transparent p-0 w-100 text-start" type="submit">
                    <i class="bi bi-box-arrow-right me-2"></i> Đăng xuất
                </button>
            </form>
        </div>
    </div>

    <div class="main-content">
        <header class="bg-white shadow-sm py-3 px-4 mb-4">
            <h5 class="mb-0">@yield('title')</h5>
        </header>
        <div class="container-fluid px-4">
            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
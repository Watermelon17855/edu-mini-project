<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Edu-Mini Project')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        html,
        body {
            height: 100%;
        }

        body {
            display: flex;
            flex-direction: column;
        }

        main {
            flex: 1 0 auto;
        }

        footer {
            flex-shrink: 0;
        }

        .course-card {
            transition: transform 0.3s;
        }

        .course-card:hover {
            transform: translateY(-5px);
        }

        .navbar {
            position: relative;
            z-index: 1050 !important;
        }

        .navbar .form-control:focus {
            box-shadow: none;
            background-color: #fff;
        }

        .navbar .input-group:hover {
            opacity: 0.9;
        }

        /* Đảm bảo trên mobile không bị dính sát lề */
        @media (max-width: 991px) {
            .navbar form {
                margin-bottom: 15px;
            }
        }

        .dropdown-menu {
            z-index: 9999 !important;
        }
    </style>
</head>

<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4 shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('client.home') }}">EDU-HUB</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <form action="{{ route('client.home') }}" method="GET" class="d-flex mx-auto position-relative" style="max-width: 400px; width: 100%;">
                    <div class="input-group">
                        <input type="text" name="search" id="search-input" autocomplete="off"
                            class="form-control border-0 rounded-start-pill ps-3"
                            placeholder="Tìm khóa học...">
                        <button class="btn btn-light rounded-end-pill px-3" type="submit">
                            <i class="bi bi-search text-primary"></i>
                        </button>
                    </div>

                    <div id="search-suggestions" class="dropdown-menu w-100 shadow border-0 mt-2 p-0 overflow-hidden"
                        style="display: none; position: absolute; top: 100%; left: 0;">
                    </div>
                </form>
                <ul class="navbar-nav ms-auto">
                    @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white fw-bold" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> Chào, {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                            @if(Auth::user()->role === 'admin')
                            <li>
                                <a class="dropdown-item text-primary fw-bold" href="{{ route('admin.dashboard') }}">
                                    <i class="bi bi-speedometer2 me-2"></i>Trang quản trị
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            @endif

                            <li><a class="dropdown-item" href="{{ route('client.my_courses') }}">Khóa học của tôi</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">Đăng xuất</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                    @else
                    <li class="nav-item">
                        <a class="nav-link text-white btn btn-warning btn-sm text-dark ms-2 rounded-pill px-3 fw-bold shadow-sm" href="{{ route('login') }}">
                            Đăng nhập
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white btn btn-warning btn-sm text-dark ms-2 rounded-pill px-3 fw-bold shadow-sm" href="{{ route('register') }}">
                            Đăng ký
                        </a>
                    </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <main>
        <div class="container mt-3" id="alert-container">
            @if(session('error'))
            <div class="alert alert-danger fade show rounded-pill px-4 shadow-sm border-0 mb-3 auto-hide">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            </div>
            @endif

            @if(session('success'))
            <div class="alert alert-success fade show rounded-pill px-4 shadow-sm border-0 mb-3 auto-hide">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            </div>
            @endif

            @if(session('info'))
            <div class="alert alert-info fade show rounded-pill px-4 shadow-sm border-0 mb-3 auto-hide">
                <i class="bi bi-info-circle-fill me-2"></i> {{ session('info') }}
            </div>
            @endif
        </div>

        @yield('content')
    </main>

    <footer class="bg-white text-center py-4 border-top text-secondary mt-5">
        <div class="container">
            &copy; 2026 Edu-Mini Project - Laravel MVC
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const searchInput = document.getElementById('search-input');
        const suggestionBox = document.getElementById('search-suggestions');

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const query = this.value;

                if (query.length < 2) {
                    suggestionBox.style.display = 'none';
                    return;
                }

                // Gọi tới route trang chủ (index) để lấy dữ liệu JSON
                fetch(`{{ route('client.home') }}?search=${query}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.length > 0) {
                            let html = '';
                            data.forEach(course => {
                                // Tạo từng dòng kết quả trong bảng sổ xuống
                                html += `
                                <a href="/course/${course.id}" class="dropdown-item d-flex align-items-center py-2 border-bottom">
                                    <img src="${course.thumbnail}" style="width: 45px; height: 30px; object-fit: cover;" class="rounded me-2">
                                    <div class="text-truncate">
                                        <div class="fw-bold small text-dark">${course.title}</div>
                                    </div>
                                </a>`;
                            });
                            suggestionBox.innerHTML = html;
                            suggestionBox.style.display = 'block';
                        } else {
                            suggestionBox.style.display = 'none';
                        }
                    })
                    .catch(err => console.log('Lỗi tìm kiếm:', err));
            });

            // Click ra ngoài thì ẩn bảng gợi ý
            document.addEventListener('click', function(e) {
                if (!searchInput.contains(e.target) && !suggestionBox.contains(e.target)) {
                    suggestionBox.style.display = 'none';
                }
            });
        }
    </script>
</body>

</html>
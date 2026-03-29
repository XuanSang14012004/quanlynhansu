<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Thông tin công ty - {{ setting('company.name', 'DataTech') }}</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="{{ asset('css/info/info.css') }}" rel="stylesheet">
    <link href="{{ asset('css/trangchu/trangchu.css') }}" rel="stylesheet">
    <link rel="shortcut icon" href="{{ asset('/images/logo.png') }}" />

</head>

<body>

    <header class="header" id="header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                {{-- <a href="/" class="logo">DataTech</a> --}}
                <a href="/" class="logo">
                    {{-- Thay 'images/logo.png' bằng đường dẫn thực tế của bạn trong thư mục public --}}
                    <img src="{{ asset('images/Datatech.png') }}" alt="DataTech Logo">
                </a>
                <nav class="d-none d-lg-flex align-items-center gap-4">
                    <a href="/" class="nav-link">Trang chủ</a>
                    <a href="{{ route('news.public') }}" class="nav-link">Tin Tức</a>
                    <a href="{{ route('company.show') }}" class="nav-link">Thông tin</a>

                    @if (Auth::check())
                        {{-- Gọi button Dashboard --}}
                        @include('trangchu.button', [
                            'href' => route('dashboard'),
                            'class' => 'btn-login',
                            'content' => '<i class="bi bi-speedometer2"></i> Dashboard',
                        ])
                    @else
                        {{-- Gọi button Đăng nhập --}}
                        @include('trangchu.button', [
                            'href' => route('login'),
                            'class' => 'btn-login',
                            'content' => 'Đăng nhập',
                        ])
                    @endif
                </nav>
                <button class="btn btn-link d-lg-none text-dark" onclick="toggleMobileMenu()">
                    <i class="bi bi-list" style="font-size: 1.5rem;"></i>
                </button>
            </div>
        </div>
    </header>

    <div class="company-header-bg">
        <div class="container header-content">
            <h1 class="fw-bold">Thông tin công ty</h1>
            <p class="opacity-75">Thông tin chính thức về doanh nghiệp và liên hệ</p>
        </div>
    </div>

    <div class="container pb-5">
        <div class="row">

            <div class="col-lg-4">
                <div class="card card-custom h-100">
                    <div class="logo-box">
                        <img src="{{ setting('company.logo', '') != '' ? url('/uploads/logos/' . setting('company.logo')) : 'https://via.placeholder.com/200x200?text=No+Logo' }}"
                            alt="Company Logo" class="img-fluid">
                        <h3 class="company-title-sidebar">{{ setting('company.name', 'Tên Doanh Nghiệp') }}</h3>
                        <div class="badge bg-primary bg-opacity-10 text-primary mt-2 px-3 py-2 rounded-pill">
                            Đang hoạt động
                        </div>
                    </div>
                    <ul class="info-list">
                        <li>
                            <i class="bi bi-geo-alt-fill"></i>
                            <div>
                                <span class="info-label">Trụ sở chính</span>
                                <span class="info-value">{{ setting('company.address', 'Chưa cập nhật') }}</span>
                            </div>
                        </li>
                        <li>
                            <i class="bi bi-telephone-fill"></i>
                            <div>
                                <span class="info-label">Hotline</span>
                                <span class="info-value">
                                    <a
                                        href="tel:{{ setting('company.phone') }}">{{ setting('company.phone', '---') }}</a>
                                </span>
                            </div>
                        </li>
                        <li>
                            <i class="bi bi-globe"></i>
                            <div>
                                <span class="info-label">Website</span>
                                <span class="info-value">
                                    @if (setting('company.website'))
                                        <a href="{{ setting('company.website') }}"
                                            target="_blank">{{ setting('company.website') }}</a>
                                    @else
                                        ---
                                    @endif
                                </span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card card-custom">
                    <div class="card-header-custom">
                        <i class="bi bi-info-circle-fill"></i> Thông Tin Chung
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="text-muted small fw-bold text-uppercase mb-1">Tên Công Ty</label>
                                <p class="fs-5 fw-bold text-dark mb-0">{{ setting('company.name', '---') }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small fw-bold text-uppercase mb-1">Mã số thuế / Fax</label>
                                <p class="fs-5 fw-bold text-dark mb-0">{{ setting('company.fax', '---') }}</p>
                            </div>
                            <div class="col-12">
                                <hr class="text-muted opacity-25">
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small fw-bold text-uppercase mb-1">Quốc gia</label>
                                <p class="mb-0"><i
                                        class="bi bi-flag-fill text-danger me-2"></i>{{ setting('company.quoc_tich', 'Việt Nam') }}
                                </p>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small fw-bold text-uppercase mb-1">Lĩnh vực</label>
                                <p class="mb-0">Công nghệ & Dữ liệu</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card card-custom">
                    <div class="card-header-custom">
                        <i class="bi bi-person-badge-fill"></i> Người Đại Diện Pháp Luật
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-custom table-hover mb-0">
                                <tbody>
                                    <tr>
                                        <td><i class="bi bi-person me-2"></i> Họ và tên</td>
                                        <td class="text-uppercase text-primary fs-5">
                                            {{ setting('company.nguoi_dai_dien', '---') }}</td>
                                    </tr>
                                    <tr>
                                        <td><i class="bi bi-briefcase me-2"></i> Chức vụ</td>
                                        <td>{{ setting('company.chuc_vu', '---') }}</td>
                                    </tr>
                                    <tr>
                                        <td><i class="bi bi-geo me-2"></i> Quốc tịch</td>
                                        <td>{{ setting('company.quoc_tich', '---') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card card-custom">
                    <div class="card-header-custom">
                        <i class="bi bi-map-fill"></i> Bản Đồ Vị Trí
                    </div>

                    <div class="card-body p-0">
                        @if (setting('company.address'))
                            <iframe width="100%" height="350" style="border:0; min-height: 350px;"
                                allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"
                                src="https://maps.google.com/maps?q={{ urlencode(setting('company.address')) }}&t=&z=15&ie=UTF8&iwloc=&output=embed">
                            </iframe>
                        @else
                            <div class="d-flex align-items-center justify-content-center text-muted"
                                style="height: 350px; background: #eee;">
                                <div class="text-center">
                                    <i class="bi bi-geo-alt-fill fs-1"></i>
                                    <p class="mt-2">Chưa cập nhật địa chỉ để hiển thị bản đồ</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="fab-container">
        @can('update-company')
            <a href="{{ route('company.index') }}" class="btn-fab btn-edit" title="Chỉnh sửa thông tin"
                data-bs-toggle="tooltip" data-bs-placement="left">
                <i class="bi bi-pencil-fill"></i>
            </a>
        @endcan

        <a href="{{ route('home') }}" class="btn-fab btn-back" title="Quay lại trang chủ" data-bs-toggle="tooltip"
            data-bs-placement="left">
            <i class="bi bi-house-door-fill"></i>
        </a>
    </div>

    <!-- Footer -->
    @include('trangchu.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/news/index.js') }}"></script>

    {{-- <script>
        // Kích hoạt Tooltip của Bootstrap
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    </script> --}}
</body>

</html>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Cổng thông tin Công ty</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f8f9fa; }
        /* Navbar */
        .navbar { box-shadow: 0 2px 4px rgba(0,0,0,.1); background: #fff !important; }
        .nav-link { font-weight: 600; color: #333 !important; }
        
        /* Footer */
        footer { background: #222; color: #ccc; padding: 40px 0; margin-top: 50px; }
        
        /* Card tin tức */
        .news-card { border: none; box-shadow: 0 2px 10px rgba(0,0,0,0.05); transition: 0.3s; background: #fff; height: 100%; }
        .news-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
        .news-card img { height: 200px; object-fit: cover; border-radius: 4px 4px 0 0; }
        .news-card .card-body { padding: 20px; }
        .news-title { font-size: 18px; font-weight: 700; margin-bottom: 10px; line-height: 1.4; }
        .news-title a { color: #333; text-decoration: none; }
        .news-title a:hover { color: #0056b3; }
        .news-meta { font-size: 13px; color: #888; margin-bottom: 15px; }
        
        /* Hero Section (Tin nổi bật) */
        .hero-section { position: relative; margin-bottom: 30px; border-radius: 8px; overflow: hidden; }
        .hero-img { width: 100%; height: 450px; object-fit: cover; }
        .hero-content { position: absolute; bottom: 0; left: 0; right: 0; background: linear-gradient(to top, rgba(0,0,0,0.9), transparent); padding: 40px 20px 20px; color: #fff; }
        .hero-title { font-size: 32px; font-weight: bold; text-shadow: 1px 1px 3px rgba(0,0,0,0.5); }
        .hero-title a { color: #fff; text-decoration: none; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand font-weight-bold" href="{{ route('news.public') }}">
                <i class="fa fa-newspaper-o text-primary"></i> TIN TỨC CÔNG TY
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item"><a class="nav-link" href="{{ route('news.public') }}">Trang chủ</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Sự kiện</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Thông báo</a></li>
                    @if(Auth::check())
                        <li class="nav-item"><a class="btn btn-primary btn-sm ml-3 text-white" href="{{ route('dashboard') }}">Vào trang quản trị</a></li>
                    @else
                        <li class="nav-item"><a class="btn btn-outline-primary btn-sm ml-3" href="/login">Đăng nhập</a></li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <div style="height: 80px;"></div>

    <div class="container" style="min-height: 600px;">
        @yield('content')
    </div>

    <footer>
        <div class="container text-center">
            <p>&copy; {{ date('Y') }} Công ty Cổ phần ABC. All rights reserved.</p>
            <small>Hệ thống quản trị nội bộ</small>
        </div>
    </footer>

</body>
</html>
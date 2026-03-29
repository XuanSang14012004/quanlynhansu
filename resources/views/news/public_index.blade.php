<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    {{-- 1. SEO META TAGS --}}
    @php
    $pageTitle = 'Tin Tức & Sự Kiện - DataTech';
    if(request()->has('page') && request('page') > 1) {
    $pageTitle .= ' - Trang ' . request('page');
    }
    $pageDesc = 'Cập nhật tin tức công nghệ, hoạt động doanh nghiệp và tuyển dụng mới nhất từ DataTech.';
    @endphp

    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $pageDesc }}">

    {{-- Canonical & Pagination Links --}}
    <link rel="canonical" href="{{ url()->current() }}" />
    @if($news->previousPageUrl())
    <link rel="prev" href="{{ $news->previousPageUrl() }}" />
    @endif
    @if($news->nextPageUrl())
    <link rel="next" href="{{ $news->nextPageUrl() }}" />
    @endif

    {{-- Open Graph --}}
    <meta property="og:type" content="website" />
    <meta property="og:title" content="{{ $pageTitle }}" />
    <meta property="og:description" content="{{ $pageDesc }}" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:image" content="{{ asset('images/Datatech-banner-share.png') }}" />
    <meta property="og:site_name" content="DataTech" />

    {{-- Schema Markup --}}
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "CollectionPage",
            "headline": "Tin Tức & Sự Kiện DataTech",
            "description": "{{ $pageDesc }}",
            "url": "{{ url()->current() }}",
            "publisher": {
                "@type": "Organization",
                "name": "DataTech",
                "logo": {
                    "@type": "ImageObject",
                    "url": "{{ asset('images/Datatech.png') }}"
                }
            }
        }
    </script>

    {{-- Fonts & CSS --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <link href="{{ asset('css/news/public_index.css') }}" rel="stylesheet">
    <link href="{{ asset('css/trangchu/trangchu.css') }}" rel="stylesheet">
    <link rel="shortcut icon" href="{{ asset('/images/logo.png') }}" />

    {{-- Inline CSS fix --}}
    <style>
        .card-link-wrapper {
            text-decoration: none;
            color: inherit;
            display: block;
            height: 100%;
        }

        .card-link-wrapper:hover .news-title,
        .card-link-wrapper:hover .featured-title {
            color: var(--primary-color);
            transition: 0.3s;
        }

        /* Badge danh mục */
    </style>
</head>

<body>
    {{-- HEADER --}}
    <header class="header" id="header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <a href="/" class="logo">
                    <img src="{{ asset('images/Datatech.png') }}" alt="DataTech Logo">
                </a>
                <nav class="d-none d-lg-flex align-items-center gap-4">
                    <a href="/" class="nav-link">Trang chủ</a>
                    <a href="{{ route('news.public') }}" class="nav-link" style="color: var(--primary-color);">Tin Tức</a>
                    <a href="{{ route('company.show') }}" class="nav-link">Thông tin</a>

                    @if (Auth::check())
                    <a href="{{ route('dashboard') }}" class="btn-login">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                    @else
                    <a href="{{ route('login') }}" class="btn-login">Đăng nhập</a>
                    @endif
                </nav>
                <button class="btn btn-link d-lg-none text-dark">
                    <i class="bi bi-list" style="font-size: 1.5rem;"></i>
                </button>
            </div>
        </div>
    </header>

    {{-- PAGE TITLE --}}
    <section class="page-header">
        <div class="container">
            <div class="text-center">
                <h1>Tin Tức & Sự Kiện</h1>
                <p>Cập nhật những thông tin mới nhất về công nghệ, văn hóa doanh nghiệp và hoạt động của DataTech</p>
            </div>
        </div>
    </section>

    {{-- SEARCH & FILTER --}}
    <section class="search-filter-section">
        <div class="container">
            <form action="{{ route('news.public') }}" method="GET">
                <div class="row align-items-center g-3">
                    {{-- 1. Ô tìm kiếm --}}
                    <div class="col-lg-6">
                        <div class="search-box">
                            <button type="submit"
                                style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%); border: none; background: none; color: #64748b; font-size: 1.2rem; cursor: pointer; z-index: 10;">
                                <i class="bi bi-search"></i>
                            </button>
                            <input type="text" name="keyword" value="{{ request('keyword') }}"
                                placeholder="Tìm kiếm tin tức, sự kiện..." id="searchInput">

                            {{-- Giữ lại tham số category khi tìm kiếm (nếu đang lọc) --}}
                            @if(request('category'))
                            <input type="hidden" name="category" value="{{ request('category') }}">
                            @endif
                        </div>
                    </div>

                    {{-- 2. Bộ lọc danh mục (ĐÃ CẬP NHẬT) --}}
                    <div class="col-lg-6">
                        <div class="text-lg-end">
                            <div class="dropdown d-inline-block">

                                {{-- NÚT KÍCH HOẠT (ĐÃ SỬA) --}}
                                {{-- LƯU Ý QUAN TRỌNG: Đã xóa class 'dropdown-toggle' ở dòng dưới --}}
                                <button class="btn btn-outline-secondary d-flex justify-content-between align-items-center px-4"
                                    type="button"
                                    id="categoryDropdown"
                                    data-bs-toggle="dropdown"
                                    aria-expanded="false"
                                    style="height: 60px; min-width: 220px; border-radius: 50px; border-color: #ddd; color: #333; background-color: #fff;">

                                    <span class="fw-medium text-truncate" style="max-width: 150px;">
                                        @if(request('category') && isset($categories))
                                        {{ $categories->firstWhere('slug', request('category'))->name ?? 'Danh mục' }}
                                        @else
                                        Tất cả danh mục
                                        @endif
                                    </span>

                                    {{-- Chúng ta chỉ giữ lại mũi tên icon đẹp này thôi --}}
                                    <i class="bi bi-chevron-down small text-muted ms-2"></i>
                                </button>

                                {{-- Danh sách xổ xuống (Giữ nguyên) --}}
                                <ul class="dropdown-menu dropdown-menu-end mt-2 shadow-sm" aria-labelledby="categoryDropdown" style="border-radius: 15px; border: 1px solid #eee; min-width: 220px;">
                                    <li>
                                        <a class="dropdown-item py-2 px-3 {{ !request('category') ? 'active bg-light text-primary' : '' }}"
                                            href="{{ route('news.public') }}">
                                            <i class="bi bi-grid-fill me-2"></i> Tất cả
                                        </a>
                                    </li>
                                    @if(isset($categories))
                                    @foreach($categories as $cat)
                                    <li>
                                        <a class="dropdown-item py-2 px-3 {{ request('category') == $cat->slug ? 'active bg-light text-primary' : '' }}"
                                            href="{{ route('news.public', ['category' => $cat->slug]) }}">
                                            <i class="bi bi-tag-fill me-2"></i> {{ $cat->name }}
                                        </a>
                                    </li>
                                    @endforeach
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>

    @php
    $featured = $news->first();
    @endphp

    {{-- FEATURED NEWS (BÀI NỔI BẬT) --}}
    @if ($featured && $news->currentPage() == 1)
    <section class="featured-news">
        <div class="container">
            <a href="{{ route('news.show', ['id' => $featured->id, 'slug' => $featured->slug]) }}" class="card-link-wrapper">
                <div class="featured-card">
                    <img src="{{ $featured->thumbnail ? asset($featured->thumbnail) : '' }}"
                        alt="{{ $featured->title }}">
                    <div class="featured-overlay">
                        {{-- Hiển thị tên Danh mục --}}
                        <span class="featured-category">
                            {{ $featured->category->name ?? 'Tin nổi bật' }}
                        </span>

                        <h2 class="featured-title">{{ $featured->title }}</h2>
                        <div class="featured-meta">
                            <span><i class="bi bi-person-circle"></i> {{ $featured->author->name ?? 'Admin' }}</span>
                            <span><i class="bi bi-calendar3"></i> {{ $featured->created_at->format('d/m/Y') }}</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </section>
    @endif

    {{-- NEWS GRID (DANH SÁCH BÀI VIẾT) --}}
    <section class="news-grid">
        <div class="container">
            <div class="row g-4" id="newsContainer">

                @if ($news->isEmpty())
                <div class="col-12 text-center py-5">
                    <h3 class="text-muted">Chưa có bài viết nào trong danh mục này.</h3>
                    <a href="{{ route('news.public') }}" class="btn btn-outline-primary mt-3">Quay lại tất cả tin tức</a>
                </div>
                @else
                @foreach ($news as $item)
                {{-- Bỏ qua bài đầu tiên nếu đang ở trang 1 (vì đã hiện ở Featured) --}}
                @if ($loop->first && $news->currentPage() == 1)
                @continue
                @endif

                <div class="col-lg-4 col-md-6">
                    <a href="{{ route('news.show', ['id' => $item->id, 'slug' => $item->slug]) }}" class="card-link-wrapper">
                        <div class="news-card">
                            <div class="news-image">
                                <img src="{{ $item->thumbnail ? asset($item->thumbnail) : '' }}"
                                    alt="{{ $item->title }}" loading="lazy">

                                {{-- Hiển thị tên Danh mục --}}
                                <span class="news-category">
                                    {{ $item->category->name ?? 'Tin tức' }}
                                </span>
                            </div>
                            <div class="news-content">
                                <h3 class="news-title">{{ $item->title }}</h3>
                                <div class="news-excerpt">
                                    {!! Str::limit(strip_tags($item->content), 120) !!}
                                </div>
                                <div class="news-footer">
                                    <div class="news-author">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($item->author->name ?? 'User') }}&background=random"
                                            alt="{{ $item->author->name ?? 'Author' }}" class="author-avatar" loading="lazy">
                                        <div class="author-info">
                                            <div class="author-name">{{ $item->author->name ?? 'Admin' }}</div>
                                            <div class="news-date">{{ $item->created_at->format('d/m/Y') }}</div>
                                        </div>
                                    </div>
                                    <span class="read-more">
                                        Xem thêm <i class="bi bi-arrow-right"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
                @endif

            </div>
        </div>
    </section>

    {{-- PAGINATION --}}
    <section class="pagination-section">
        {{-- appends(request()->query()) giúp giữ lại các tham số search/filter khi chuyển trang --}}
        {{ $news->appends(request()->query())->links() }}
    </section>

    {{-- NEWSLETTER --}}
    <section class="newsletter-section">
        <div class="container">
            <div class="newsletter-content">
                <h2>Đăng ký nhận tin tức</h2>
                <p>Cập nhật những thông tin mới nhất về DataTech và xu hướng công nghệ</p>
                <form class="newsletter-form" onsubmit="subscribeNewsletter(event)">
                    <input type="email" placeholder="Nhập địa chỉ email của bạn..." required>
                    <button type="submit">
                        <i class="bi bi-envelope-fill me-2"></i> Đăng ký
                    </button>
                </form>
            </div>
        </div>
    </section>

    @include('trangchu.footer')

    {{-- SCROLL TOP --}}
    <a href="#" class="scroll-top" id="scrollTop">
        <i class="bi bi-arrow-up-short"></i>
    </a>

    {{-- SCRIPTS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/news/index.js') }}"></script>
</body>

</html>
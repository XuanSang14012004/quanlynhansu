<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    
    {{-- SEO META TAGS --}}
    <title>{{ $post->title }} - DataTech</title>
    <meta name="description" content="{{ $post->summary ? Str::limit($post->summary, 160) : Str::limit(strip_tags($post->content), 160) }}">
    <link rel="canonical" href="{{ route('news.show', ['id' => $post->id, 'slug' => $post->slug]) }}" />
    
    {{-- Open Graph --}}
    <meta property="og:type" content="article" />
    <meta property="og:title" content="{{ $post->title }}" />
    <meta property="og:description" content="{{ $post->summary ? Str::limit($post->summary, 200) : Str::limit(strip_tags($post->content), 200) }}" />
    <meta property="og:image" content="{{ asset($post->thumbnail) }}" />
    <meta property="og:url" content="{{ route('news.show', ['id' => $post->id, 'slug' => $post->slug]) }}" />
    <meta property="og:site_name" content="DataTech" />
    
    {{-- Schema Markup --}}
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "NewsArticle",
      "headline": "{{ $post->title }}",
      "image": ["{{ asset($post->thumbnail) }}"],
      "datePublished": "{{ $post->created_at->toIso8601String() }}",
      "dateModified": "{{ $post->updated_at->toIso8601String() }}",
      "author": [{
          "@type": "Person",
          "name": "{{ $post->author->name ?? 'Admin' }}"
        }],
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

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <link href="{{ asset('css/news/show.css') }}" rel="stylesheet">
    <link href="{{ asset('css/trangchu/trangchu.css') }}" rel="stylesheet">
    <link rel="shortcut icon" href="{{ asset('/images/logo.png') }}" />

    
    <style>
        .related-card-link {
            text-decoration: none;
            color: inherit;
            display: block;
            height: 100%;
        }
        .related-card-link:hover .related-card-title {
            color: var(--primary-color);
        }
        /* Style cho Badge Danh mục */
        .article-category, .related-card-category {
            display: inline-block;
            background-color: var(--primary-color, #0d6efd); /* Màu xanh mặc định nếu chưa có biến */
            color: #fff;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <div class="progress-bar" id="progressBar"></div>

    <header class="header" id="header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <a href="/" class="logo"> <img src="{{ asset('images/Datatech.png') }}" alt="DataTech Logo"></a>
                <nav class="d-none d-lg-flex align-items-center gap-4">
                    <a href="/" class="nav-link">Trang chủ</a>
                    <a href="{{ route('news.public') }}" class="nav-link" style="color: var(--primary-color);">Tin Tức</a>
                    <a href="{{ route('company.show') }}" class="nav-link">Thông tin</a>

                    @if (Auth::check())
                        <a href="{{ route('dashboard') }}" class="btn-login">Dashboard</a>
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

    {{-- BREADCRUMB --}}
    <section class="breadcrumb-section">
        <div class="container">
            <div class="breadcrumb-custom">
                <a href="/">Trang chủ</a>
                <i class="bi bi-chevron-right"></i>
                <a href="{{ route('news.public') }}">Tin tức</a>
                <i class="bi bi-chevron-right"></i>
                
                {{-- Breadcrumb chi tiết hơn: Thêm danh mục vào đường dẫn --}}
                @if($post->category)
                    <a href="{{ route('news.public', ['category' => $post->category->slug]) }}">
                        {{ $post->category->name }}
                    </a>
                    <i class="bi bi-chevron-right"></i>
                @endif
                
                <span class="active">{{ Str::limit($post->title, 50) }}</span>
            </div>
        </div>
    </section>

    <article>
        <div class="container">
            <div class="article-header">
                {{-- 1. HIỂN THỊ DANH MỤC BÀI VIẾT --}}
                <span class="article-category">
                    {{ $post->category->name ?? 'Tin tức chung' }}
                </span>
                
                <h1 class="article-title">{{ $post->title }}</h1>

                <div class="article-meta">
                    <div class="author-info">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($post->author->name ?? 'Admin') }}&background=random"
                            alt="Author" class="author-avatar">
                        <div class="author-details">
                            <h5>{{ $post->author->name ?? 'Ban biên tập' }}</h5>
                            <p>Tác giả</p>
                        </div>
                    </div>

                    <div class="meta-item">
                        <i class="bi bi-calendar3"></i>
                        <span>{{ $post->created_at->format('d/m/Y') }}</span>
                    </div>

                    @php
                        $wordCount = str_word_count(strip_tags($post->content));
                        $readingTime = ceil($wordCount / 200);
                    @endphp
                    <div class="meta-item">
                        <i class="bi bi-clock"></i>
                        <span>{{ $readingTime }} phút đọc</span>
                    </div>

                    <div class="social-share">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                            target="_blank" class="share-btn" title="Chia sẻ Facebook">
                            <i class="bi bi-facebook"></i>
                        </a>
                        <a href="#" class="share-btn copy-link-btn" title="Copy link">
                            <i class="bi bi-link-45deg"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        @if ($post->thumbnail)
            <div class="container">
                <div class="featured-image">
                    <img src="{{ asset($post->thumbnail) }}" alt="{{ $post->title }}">
                </div>
            </div>
        @endif

        <div class="container">
            <div class="article-content">
                @if ($post->summary)
                    <p style="font-weight: 500; font-size: 1.2rem; color: var(--text-dark);">
                        {{ $post->summary }}
                    </p>
                @endif

                {!! $post->content !!}

                <div class="author-bio">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($post->author->name ?? 'Admin') }}&size=120&background=random"
                        alt="Author" class="author-bio-avatar">
                    <div class="author-bio-content">
                        <h4>{{ $post->author->name ?? 'Ban biên tập' }}</h4>
                        <p>Tác giả tại DataTech</p>
                        <p class="bio-text">Cảm ơn bạn đã theo dõi bài viết này. Hãy chia sẻ nếu bạn thấy hữu ích!</p>
                    </div>
                </div>

            </div>
        </div>
    </article>

    {{-- BÀI VIẾT LIÊN QUAN --}}
    <section class="related-posts">
        <div class="container">
            <div class="section-title">
                <h2>Bài viết cùng chuyên mục</h2>
                <p>Khám phá thêm những tin tức liên quan</p>
            </div>

            <div class="row g-4">
                @if ($relatedPosts->count() > 0)
                    @foreach ($relatedPosts as $related)
                        <div class="col-lg-4 col-md-6">
                            <a href="{{ route('news.show', ['id' => $related->id, 'slug' => $related->slug]) }}" class="related-card-link">
                                <div class="related-card">
                                    <div class="related-card-image">
                                        <img src="{{ $related->thumbnail ? asset($related->thumbnail) : '' }}"
                                            alt="{{ $related->title }}" loading="lazy">
                                    </div>
                                    <div class="related-card-content">
                                        
                                        {{-- 2. HIỂN THỊ DANH MỤC CỦA BÀI LIÊN QUAN --}}
                                        <div class="related-card-category">
                                            {{ $related->category->name ?? 'Tin tức' }}
                                        </div>
                                        
                                        <h4 class="related-card-title">{{ Str::limit($related->title, 60) }}</h4>
                                        <div class="related-card-date">
                                            <i class="bi bi-calendar3"></i>
                                            {{ $related->created_at->format('d/m/Y') }}
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                @else
                    <div class="col-12 text-center">
                        <p>Chưa có bài viết liên quan nào.</p>
                    </div>
                @endif
            </div>
        </div>
    </section>

    @include('trangchu.footer')

    <a href="#" class="scroll-top" id="scrollTop"><i class="bi bi-arrow-up-short"></i></a>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/news/index.js') }}"></script>
</body>

</html>
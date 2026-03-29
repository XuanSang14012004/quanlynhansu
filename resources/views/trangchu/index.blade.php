<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>DataTech - Hệ thống Quản lý Nhân sự Thông minh</title>
    <meta name="description"
        content="Nền tảng quản lý nhân sự hiện đại, tích hợp AI và phân tích dữ liệu cho doanh nghiệp công nghệ">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="{{ asset('css/trangchu/trangchu.css') }}" rel="stylesheet">
    <link rel="shortcut icon" href="{{ asset('/images/logo.png') }}" />

</head>

<body>
    <!-- Header -->
    @include('trangchu.header')

    <!-- Hero Section -->
    @include('trangchu.banner')

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-3 col-sm-6">
                    <div class="stat-card">
                        <div class="stat-number">500+</div>
                        <div class="stat-label">Nhân viên tài năng</div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="stat-card">
                        <div class="stat-number">150+</div>
                        <div class="stat-label">Dự án thành công</div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="stat-card">
                        <div class="stat-number">10+</div>
                        <div class="stat-label">Năm kinh nghiệm</div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="stat-card">
                        <div class="stat-number">98%</div>
                        <div class="stat-label">Hài lòng nhân viên</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="features-section">
        <div class="container">
            <div class="section-title">
                <h2>Tính năng cốt lõi</h2>
                <p>Hệ thống tập trung vào các chức năng quản trị quan trọng nhất cho doanh nghiệp</p>
            </div>

            <div class="row g-4 justify-content-center">

                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-person-lines-fill"></i>
                        </div>
                        <h4>Quản trị nhân sự</h4>
                        <p>Quản lý toàn diện hồ sơ nhân viên, hợp đồng lao động, sơ đồ tổ chức phòng ban và quá trình
                            công tác.</p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-newspaper"></i>
                        </div>
                        <h4>Quản trị tin tức</h4>
                        <p>Hệ thống CMS cho phép đăng tải thông báo nội bộ, sự kiện và tin tức hoạt động của công ty
                            nhanh chóng.</p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-person-gear"></i>
                        </div>
                        <h4>Quản trị người dùng</h4>
                        <p>Phân quyền (RBAC) chi tiết theo vai trò, quản lý tài khoản đăng nhập và kiểm soát bảo mật hệ
                            thống.</p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Benefits Section -->
    <section id="benefits" class="benefits-section">
        <div class="container">
            <div class="section-title">
                <h2>Tại sao chọn DataTech?</h2>
                <p>Môi trường làm việc lý tưởng cho những tài năng công nghệ</p>
            </div>
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=800"
                        class="img-fluid rounded shadow-lg" alt="Team working">
                </div>
                <div class="col-lg-6">
                    <div class="benefit-item">
                        <div class="benefit-icon">
                            <i class="bi bi-rocket-takeoff"></i>
                        </div>
                        <div class="benefit-content">
                            <h4>Cơ hội thăng tiến rõ ràng</h4>
                            <p>Lộ trình sự nghiệp minh bạch, đánh giá định kỳ, cơ hội quản lý và chuyên gia kỹ thuật</p>
                        </div>
                    </div>
                    <div class="benefit-item">
                        <div class="benefit-icon">
                            <i class="bi bi-cash-stack"></i>
                        </div>
                        <div class="benefit-content">
                            <h4>Đãi ngộ hấp dẫn nhất thị trường</h4>
                            <p>Lương cạnh tranh top 10%, thưởng hiệu suất, ESOP, bảo hiểm sức khỏe cao cấp cho cả gia
                                đình</p>
                        </div>
                    </div>
                    <div class="benefit-item">
                        <div class="benefit-icon">
                            <i class="bi bi-globe2"></i>
                        </div>
                        <div class="benefit-content">
                            <h4>Làm việc với chuyên gia quốc tế</h4>
                            <p>Dự án global, đối tác Fortune 500, cơ hội onsite nước ngoài và học hỏi công nghệ mới nhất
                            </p>
                        </div>
                    </div>
                    <div class="benefit-item">
                        <div class="benefit-icon">
                            <i class="bi bi-laptop"></i>
                        </div>
                        <div class="benefit-content">
                            <h4>Linh hoạt & Cân bằng</h4>
                            <p>Hybrid working, flexible hours, unlimited PTO, thiết bị Apple cao cấp và văn phòng hiện
                                đại</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimonials" class="testimonials-section">
        <div class="container">
            <div class="section-title">
                <h2>Góc Nhân viên</h2>
                <p>Câu chuyện thành công từ đại gia đình DataTech</p>
            </div>
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="testimonial-card">
                        <div class="testimonial-header">
                            <img src="https://i.pravatar.cc/150?img=12" alt="Nguyễn Văn A" class="testimonial-img">
                            <div class="testimonial-info">
                                <h4>Nguyễn Văn An</h4>
                                <p>Senior Data Engineer</p>
                            </div>
                        </div>
                        <div class="stars">
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                        </div>
                        <p class="testimonial-text">"Môi trường tại DataTech giúp tôi phát triển kỹ năng vượt bậc. Các
                            dự án data pipeline quy mô lớn và sự hỗ trợ từ team quốc tế thật sự tuyệt vời."</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="testimonial-card">
                        <div class="testimonial-header">
                            <img src="https://i.pravatar.cc/150?img=47" alt="Trần Thị B" class="testimonial-img">
                            <div class="testimonial-info">
                                <h4>Trần Thị Bình</h4>
                                <p>HR Business Partner</p>
                            </div>
                        </div>
                        <div class="stars">
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                        </div>
                        <p class="testimonial-text">"Văn hóa công ty luôn đặt con người lên hàng đầu. Chế độ phúc lợi,
                            các hoạt động teambuilding và cơ hội phát triển khiến tôi gắn bó suốt 5 năm."</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="testimonial-card">
                        <div class="testimonial-header">
                            <img src="https://i.pravatar.cc/150?img=33" alt="Lê Minh C" class="testimonial-img">
                            <div class="testimonial-info">
                                <h4>Lê Minh Cường</h4>
                                <p>Tech Lead - AI Division</p>
                            </div>
                        </div>
                        <div class="stars">
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                        </div>
                        <p class="testimonial-text">"Được làm việc với các công nghệ AI/ML tiên tiến nhất. DataTech
                            không chỉ là nơi làm việc mà còn là ngôi nhà thứ hai của tôi."</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="contact-section">
        <div class="container">
            <div class="section-title text-white">
                <h2 class="text-white">Liên hệ với chúng tôi</h2>
                <p class="text-white-50">Hãy để DataTech hỗ trợ bạn xây dựng đội ngũ nhân sự xuất sắc</p>
            </div>
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="contact-info-card">
                        <i class="bi bi-geo-alt"></i>
                        <h4>Địa chỉ văn phòng</h4>
                        <p>Tầng 14, D11 Sunrise Building,
                            <br>90 Trần Thái Tông, Cầu Giấy, Hà Nội
                        </p>
                    </div>
                    <div class="contact-info-card">
                        <i class="bi bi-envelope"></i>
                        <h4>Email liên hệ</h4>
                        <p>info@datatechvn.com</p>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="contact-form">
                        <form id="contactForm">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <input type="text" class="form-control" placeholder="Họ và tên *" required>
                                </div>
                                <div class="col-md-6">
                                    <input type="email" class="form-control" placeholder="Email *" required>
                                </div>
                                <div class="col-md-6">
                                    <input type="tel" class="form-control" placeholder="Số điện thoại *"
                                        required>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" placeholder="Địa chỉ *" required>
                                </div>
                                <div class="col-12">
                                    <textarea class="form-control" rows="5" placeholder="Nội dung tin nhắn *" required></textarea>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn-submit">
                                        <i class="bi bi-send me-2"></i>
                                        Gửi tin nhắn
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    @include('trangchu.footer')

    <!-- Scroll to top -->
    <a href="#" class="scroll-top" id="scrollTop">
        <i class="bi bi-arrow-up-short"></i>
    </a>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/news/index.js') }}"></script>
</body>

</html>

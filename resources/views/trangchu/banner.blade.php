{{-- resources/views/trangchu/banner.blade.php --}}
<section id="hero" class="hero">
    <div class="container h-100">
        <div class="row h-100 align-items-center">
            
            <div class="col-lg-6 hero-content">
                <h1>Quản lý Nhân sự</h1>
                <p>Nền tảng HRM tích hợp phân tích dữ liệu, tự động hóa quy trình chấm công, tính lương và tối ưu trải nghiệm nhân viên toàn diện.</p>
                
                <div class="d-flex gap-3 flex-wrap">
                    {{-- Button Khám phá --}}
                    @include('trangchu.button', [
                        'href' => '#features',
                        'class' => 'btn-primary-custom',
                        'content' => 'Khám phá tính năng'
                    ])
                    
                    {{-- Button Xem Demo --}}
                    @include('trangchu.button', [
                        'href' => '#about',
                        'class' => 'btn-secondary-custom',
                        'content' => '<i class="bi bi-play-circle-fill" style="font-size: 1.2rem;"></i> Xem demo'
                    ])
                </div>
            </div>
            
        </div>
    </div>

    <div class="hero-image-wrapper">
        <div class="hero-overlay"></div>
        <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?q=80&w=2070&auto=format&fit=crop" 
             class="hero-img-full" 
             alt="Team Working">
    </div>
</section>
document.addEventListener('DOMContentLoaded', function() {

            // 1. Khai báo biến (Lấy 1 lần dùng nhiều lần)
            const header = document.getElementById('header');
            const scrollTopBtn = document.getElementById('scrollTop');
            const progressBar = document.getElementById('progressBar');

            // 2. Lắng nghe sự kiện cuộn trang (Scroll)
            window.addEventListener('scroll', function() {
                // --- Xử lý Header & Nút BackToTop ---
                if (window.scrollY > 100) {
                    if (header) header.classList.add('scrolled');
                    if (scrollTopBtn) scrollTopBtn.classList.add('active');
                } else {
                    if (header) header.classList.remove('scrolled');
                    if (scrollTopBtn) scrollTopBtn.classList.remove('active');
                }

                // --- Xử lý Progress Bar (Nếu có) ---
                if (progressBar) {
                    const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
                    const height = document.documentElement.scrollHeight - document.documentElement
                        .clientHeight;
                    const scrolled = (winScroll / height) * 100;
                    progressBar.style.width = scrolled + '%';
                }
            });

            // 3. Xử lý khi bấm nút Scroll Top
            if (scrollTopBtn) {
                scrollTopBtn.addEventListener('click', function(e) {
                    e.preventDefault(); // Chặn việc thêm # vào URL

                    // Cách 1: Dùng window.scrollTo (Hiện đại, mượt)
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                });
            }

            // 4. Smooth scroll cho các thẻ a khác (Mục lục, neo...)
            document.querySelectorAll('a[href^="#"]:not(#scrollTop)').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    const targetId = this.getAttribute('href');
                    if (targetId === '#' || targetId === '') return;

                    const targetElement = document.querySelector(targetId);
                    if (targetElement) {
                        e.preventDefault();
                        // Trừ đi chiều cao header (80px) để không bị che mất tiêu đề
                        const headerOffset = 80;
                        const elementPosition = targetElement.getBoundingClientRect().top;
                        const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

                        window.scrollTo({
                            top: offsetPosition,
                            behavior: "smooth"
                        });
                    }
                });
            });

        });
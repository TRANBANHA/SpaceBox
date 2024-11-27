<x-my-layout>
    <x-slot name="linkcss">
        <link rel="stylesheet" href="{{ asset('assets/css/home.css') }}">
    </x-slot>

    @include('components.header')
    <div class="home">
        <!-- Hero Section -->
        <div class="landing-page flex-row">
            <div class="ldp-content flex-col">
                <h1>Khám phá không gian của bạn</h1>
                <div id="typewriter-container">
                    <p id="typewriter"></p>
                </div>
                <a href="{{ route('account.login') }}" class="btn-primary">
                    <i class="fa-solid fa-arrow-right"></i> Bắt đầu ngay
                </a>
                
            </div>
            <div class="content-demo">
                <img src="{{ asset('assets/images/demo.jpg') }}" alt="Hero Image">
            </div>
        </div>
        
        <!-- Feature Section -->
        <div class="features flex-row">
            <a href="#performance" class="feature-link">
                <div class="feature-box flex-col">
                    <i class="fa-solid fa-rocket"></i>
                    <h3>Hiệu suất cao</h3>
                    <p>Ứng dụng chạy mượt mà với trải nghiệm người dùng tối ưu.</p>
                </div></a>
            <a href="#features" class="feature-link"><div class="feature-box flex-col">
                <i class="fa-solid fa-star"></i>
                <h3>Tính năng nổi bật</h3>
                <p>Tạo các phòng chat, chia sẻ hình ảnh, và kết nối với cộng đồng dễ dàng.</p>
            </div>
            </a>
            <a href="#security" class="feature-link">
            <div class="feature-box flex-col">
                <i class="fa-solid fa-shield-alt"></i>
                <h3>Bảo mật an toàn</h3>
                <p>Thông tin của bạn được bảo vệ với các tiêu chuẩn an ninh cao nhất.</p>
            </div></a>
            

        </div>
        <div class="features flex-col">
            <section id="performance" class="feature-section full-screen">
                <div class="feature-content flex-col">
                    <h2><i class="fa-solid fa-rocket"></i> Hiệu suất cao</h2>
                    <div class="performance-grid">
                        <div class="performance-item feature-3d">
                            <img src="assets/images/demo.jpg" alt="Tốc độ">
                            <h3>Tốc độ vượt trội</h3>
                            <p>Thời gian phản hồi nhanh chóng giúp bạn không bỏ lỡ bất kỳ cơ hội nào.</p>
                        </div>
                        <div class="performance-item feature-3d">
                            <img src="assets/images/demo.jpg" alt="Tối ưu hóa">
                            <h3>Tối ưu hóa thông minh</h3>
                            <p>Ứng dụng được thiết kế để xử lý mọi tác vụ nặng nhẹ một cách mượt mà.</p>
                        </div>
                        <div class="performance-item feature-3d">
                            <img src="assets/images/demo.jpg" alt="Khả năng mở rộng">
                            <h3>Khả năng mở rộng</h3>
                            <p>Cung cấp giải pháp linh hoạt, phù hợp với mọi quy mô sử dụng.</p>
                        </div>
                    </div>
                </div>
            </section>

            <section id="features" class="feature-section">
                <h2><i class="fa-solid fa-star"></i> Tính năng nổi bật</h2>
                <div class="feature-content">
                    <img src="assets/images/demo.jpg" alt="Tính năng nổi bật" class="feature-image">
                    <div>
                        <p>
                            Chúng tôi mang đến những tính năng độc đáo để bạn có thể tận hưởng sự kết nối tốt nhất. 
                            Các phòng chat được thiết kế đơn giản nhưng hiện đại, giúp bạn giao tiếp với bạn bè, đồng nghiệp 
                            hoặc cộng đồng một cách dễ dàng.
                        </p>
                        <p>
                            Tải ảnh và chia sẻ những khoảnh khắc đáng nhớ chưa bao giờ nhanh và tiện lợi đến thế. 
                            Hệ thống của chúng tôi hỗ trợ hình ảnh chất lượng cao, đảm bảo bạn có thể lưu giữ mọi kỷ niệm đẹp.
                        </p>
                    </div>
                </div>
            </section>
            
            <section id="security" class="feature-section">
                <h2><i class="fa-solid fa-shield-alt"></i> Bảo mật an toàn</h2>
                <div class="feature-content">
                    <img src="assets/images/demo.jpg" alt="Bảo mật an toàn" class="feature-image">
                    <div>
                        <p>
                            Đừng lo lắng về việc mất thông tin hay bị lộ dữ liệu cá nhân. 
                            Chúng tôi sử dụng các công nghệ mã hóa tiên tiến nhất để đảm bảo mọi thông tin của bạn 
                            được giữ kín và bảo vệ tuyệt đối.
                        </p>
                        <p>
                            Hệ thống bảo mật tự động phát hiện và ngăn chặn các nguy cơ tiềm ẩn, giữ cho tài khoản của bạn luôn an toàn. 
                            Thêm vào đó, xác thực hai lớp (2FA) đảm bảo quyền truy cập chỉ dành cho bạn.
                        </p>
                    </div>
                </div>
            </section>
            
            
            
        </div>

        <!-- Nút Di chuyển lên đầu trang -->
        <button id="backToTop" class="back-to-top">
            <i class="fa-solid fa-arrow-up"></i>
        </button>

    </div>
    @include('components.footer')
    <script>
        document.addEventListener("DOMContentLoaded", () => {
        const text = `Tham gia vào hành trình đổi mới và sáng tạo. Tìm kiếm ý tưởng và chia sẻ những khoảnh khắc đáng nhớ của bạn!`;
        const element = document.getElementById("typewriter");
        let index = 0;

        function typeWriter() {
            if (index < text.length) {
                element.textContent += text.charAt(index);
                index++;
                setTimeout(typeWriter, 20); // Tốc độ hiển thị chữ (50ms)
            } else {
                setTimeout(() => {
                    element.textContent = ""; // Xóa nội dung
                    index = 0; // Reset chỉ số
                    typeWriter(); // Bắt đầu lại
                }, 1000); // Đợi 1 giây trước khi lặp lại
            }
        }

        typeWriter();
        
        });

        document.addEventListener("DOMContentLoaded", function() {
            const links = document.querySelectorAll('.feature-link');

            links.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault(); // Ngừng hành vi mặc định của link
                    const targetId = link.getAttribute('href').substring(1);
                    const targetElement = document.getElementById(targetId);

                    window.scrollTo({
                        top: targetElement.offsetTop - 50,  // Điều chỉnh để cuộn lên một chút
                        behavior: "smooth"  // Cuộn mượt mà
                    });
                });
            });
        });
        // Lắng nghe sự kiện cuộn
        window.addEventListener('scroll', function() {
            const backToTopButton = document.getElementById('backToTop');

            // Kiểm tra vị trí cuộn của trang
            if (window.scrollY > 200) {
                // Nếu cuộn xuống quá 200px, hiển thị nút
                backToTopButton.classList.add('show');
            } else {
                // Nếu cuộn lên trên 200px, ẩn nút
                backToTopButton.classList.remove('show');
            }
        });

        // Lắng nghe sự kiện click vào nút
        document.getElementById('backToTop').addEventListener('click', function() {
            // Cuộn lên đầu trang khi click
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        </script>
</x-my-layout>


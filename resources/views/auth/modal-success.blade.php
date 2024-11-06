<!-- Modal thông báo khi đăng ký thành công -->
<div class="modal fade modalSuccess" id="modalSuccess">
    <div class="overlay"></div>
    <div class="modal-box">
        <i class="fa-regular fa-circle-check"></i>
        <h2>{{ session('success')['title'] ?? 'Thông báo'  }}</h2>
        <h3>{{ session('success')['content'] ?? 'Nội dung thông báo không tồn tại' }}</h3>
        <div class="buttons">
            <button class="close-btn">Đồng ý</button>
        </div>
    </div>
</div>
@if(session('success'))
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Hiển thị modal khi có session success
            const Modal = document.getElementById('modalSuccess');
            Modal.classList.add('active');
            // Đóng modal khi nhấp vào overlay hoặc nút "Đồng ý"
            document.querySelector(".overlay").addEventListener("click", () => {
                Modal.classList.remove('active');
            });
            document.querySelector(".close-btn").addEventListener("click", () => {
                Modal.classList.remove('active');
            });
        });
    </script>
@endif
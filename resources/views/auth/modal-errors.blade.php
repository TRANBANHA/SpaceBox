<!-- Modal thông báo khi đăng ký không thành công -->


<div class="modal fade modalError" id="modalError">
    <div class="overlay"></div>
    <div class="modal-box">
        <i class="fa-solid fa-circle-exclamation"></i>
        <h2>{{ session('errors')['title'] ?? 'Thông báo'  }}</h2>
        <h3>{{ session('errors')['content'] ?? 'Nội dung thông báo không tồn tại' }}</h3>
        <div class="buttons">
            <button class="close-btn">Đồng ý</button>
        </div>
    </div>
</div>

@if(session('errors'))
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Hiển thị modal khi có session errors
            const Modal = document.getElementById('modalError');
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
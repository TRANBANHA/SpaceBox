import './bootstrap';

window.Echo.channel(`room`).listen('.index-room', (event) => {

    const roomBox = document.getElementById('room-list');

    const roomCurrent = document.getElementById('room_id').value; 

    const roomList = document.querySelectorAll('.chat-item'); // Lấy tất cả phòng với class
    const roomId = event.room.room_id;

    roomList.forEach((roomItem) => {
        // Kiểm tra nếu room nào có `id` trùng với `room-${roomId}`
        if (roomItem.id === `room-${roomId}`) {
            // Cập nhật tin nhắn mới nhất và thời gian
            roomItem.querySelector('.chat-info p').textContent = event.room.latestMess;
            roomItem.querySelector('.chat-meta .time').textContent = event.room.latestMessTime;
        }
    });
    let roomItem = document.getElementById(`room-${roomId}`);
    const routeUrl = roomItem.getAttribute('data-url');
    
    // Tạo phần tử HTML mới cho phòng
    const roomHTML = `
        <a href="${routeUrl}" id="room-${roomId}" class="chat-item ${event.room.room_id == roomCurrent ? 'selected-group' : ''}">
            <img src="${event.room.avt_path}" alt="Logo" class="avatar">
            <div class="chat-info">
                <h4>${event.room.room_name}</h4>
                <p style="font-weight: bold;">${event.room.latestMess}</p>
            </div>
            <div class="chat-meta">
                <span class="time">${event.room.latestMessTime}</span>
            </div>
        </a>
    `;

    

    if (roomItem) {
        // Nếu phòng đã tồn tại, cập nhật danh sách phòng
        roomBox.removeChild(roomItem); // Xóa phòng hiện tại
        roomBox.insertAdjacentHTML('afterbegin', roomHTML); 
    } else {
        // Nếu phòng chưa tồn tại, thêm phòng mới vào đầu danh sách
        roomBox.insertAdjacentHTML('afterbegin', roomHTML);
    }
});

import './bootstrap';

window.Echo.channel(`room`).listen('.index-room', (event) => {
    console.log(event);

    const roomBox = document.getElementById('room-list');

    const userCurrent = document.getElementById('userCurrent').value;
    const roomCurrent = document.getElementById('room_id'); 

    const roomList = document.querySelectorAll('.chat-item'); // Lấy tất cả phòng với class
    const roomId = event.room.room_id;

    var idRoomCurrent;
    const userRooms = event.room.userInRoom;
    console.log(userRooms);
    if (roomCurrent) {
        idRoomCurrent = roomCurrent.value;
    }
    else {
        idRoomCurrent = 0;
    }
    if (userRooms.some(userRoom => userRoom.user_id === Number(userCurrent))) {
        roomList.forEach((roomI) => {
        // Kiểm tra nếu room nào có `id` trùng với `room-${roomId}`
            if (roomI.id === `room-${roomId}`) {
                // Cập nhật tin nhắn mới nhất và thời gian
                roomI.querySelector('.chat-info p').textContent = event.room.latestMess;
                roomI.querySelector('.chat-meta .time').textContent = event.room.latestMessTime;
            }
        });
    
        let roomItem = document.getElementById(`room-${roomId}`);
        
        // Tạo phần tử HTML mới cho phòng
        const roomHTML = `
            <a href="${userCurrent == 1 ? '/admin/spacebox/r' + roomId : '/spacebox/r' + roomId}" id="room-${roomId}"  class="chat-item ${roomId == idRoomCurrent ? 'selected-group' : ''}">
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

        
        console.log(roomItem);
        if (roomItem) {
            // Nếu phòng đã tồn tại, cập nhật danh sách phòng
            roomBox.removeChild(roomItem); // Xóa phòng hiện tại
            roomBox.insertAdjacentHTML('afterbegin', roomHTML); 
        } else {
            // Nếu phòng chưa tồn tại, thêm phòng mới vào đầu danh sách
            roomBox.insertAdjacentHTML('afterbegin', roomHTML);
        }
    }
});

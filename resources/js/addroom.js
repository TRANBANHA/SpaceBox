import './bootstrap';



window.Echo.channel('addroom').listen('.add', (event) => {

    const IdUserCurrent = document.getElementById('userCurrent').value;

    const roomId = event.room.room_id;

    const userRooms = event.room.userInRoom;

   
    if (userRooms.some(userRoom => userRoom.user_id === Number(IdUserCurrent))) {
        const roomBox = document.getElementById('room-list');
        const roomHTML = `
            <a href="/spacebox/r${roomId}" id="room-${roomId}" class="chat-item">
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

        roomBox.insertAdjacentHTML('afterbegin', roomHTML);
    }
    
});
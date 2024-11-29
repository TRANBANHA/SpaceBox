import './bootstrap';



window.Echo.channel('addroom').listen('.add', (event) => {
    console.log('User added to room:', event);

        // Update the UI with the new room data
        const roomHTML = `
            <a href="${event.room.url}" id="room-${event.room.id}" class="chat-item">
                <img src="${event.room.avt_path}" alt="Logo" class="avatar">
                <div class="chat-info">
                    <h4>${event.room.room_name}</h4>
                    <p style="font-weight: bold;">${event.room.latestMess ?? '...'}</p>
                </div>
                <div class="chat-meta">
                    <span class="time">${event.room.latestMessTime ?? '...'}</span>
                </div>
            </a>
        `;

        // Append the new room to the room list
        document.getElementById('room-list').innerHTML += roomHTML;
});
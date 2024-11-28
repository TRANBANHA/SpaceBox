import './bootstrap';


/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */


const roomId = document.getElementById('room_id').value;
// console.log(roomId);
window.Echo.channel(`chat.${roomId}`)
    .listen('.chat-event', (e) => {
        const chatBox = document.querySelector('.message-chat');
        

        const userCurrent = document.getElementById('user_id').value;
        const messageHTML = `
            <div class="message ${e.message.user_id == userCurrent ? 'user' : 'friend'}">
                <img src="${e.message.img_path}" alt="Avatar" class="avatar">
                <div class="name_bubble">
                    ${!e.message.user_id == userCurrent ? `<div class="name"><span>${e.message.username}</span></div>` : ''}
                    <div class="bubble">
                        <p style="${e.message.is_deleted ? 'font-style: italic;' : ''}">
                            ${e.message.is_deleted ? 'Tin nhắn đã được thu hồi' : e.message.content}
                            <br>
                            <span>${e.message.created_at}</span>
                        </p>
                    </div>
                </div>
            </div>
        `;

        chatBox.insertAdjacentHTML('beforeend', messageHTML);

        const messageContainer = document.querySelector('.chat-content');
        messageContainer.scrollTop = messageContainer.scrollHeight;
    });


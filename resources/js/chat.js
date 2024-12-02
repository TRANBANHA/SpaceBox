import './bootstrap';

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');


const roomId = document.getElementById('room_id').value;
if (roomId) {
    window.Echo.channel(`chat.${roomId}`).listen('.chat-event', (e) => {
        const userCurrent = document.getElementById('userCurrent').value;
        const roleCurrent = document.getElementById('roleCurrent').value;

        const chatBox = document.querySelector('.message-chat');
        const userInRooms = e.message.userInRooms;

        const messageHTML = `
            <div class="message ${e.message.user_id == userCurrent ? 'user' : 'friend'}">
                <img src="${e.message.img_path}" alt="Avatar" class="avatar">
                <div class="name_bubble">
                    ${!e.message.is_current_user ? `<div class="name"><span>${e.message.username}</span></div>` : ''}
                    <div class="bubble">
                        ${!e.message.file_path ? `
                            <p style="${e.message.is_deleted ? 'font-style: italic;' : ''}">
                                ${e.message.content}
                                <br>
                                <span>${e.message.created_at}</span>
                            </p>
                        ` : `
                            ${/\.(jpg|jpeg|png|gif)$/i.test(e.message.file_path) ? `
                                <!-- Nếu là ảnh, sử dụng thẻ <img> để hiển thị -->
                                <a href="${e.message.file_path}" target="_blank">
                                    <img src="${e.message.file_path}" alt="Image" style="width: auto; max-height: 250px;" />
                                </a>
                            ` : /\.(pdf)$/i.test(e.message.file_path) ? `
                                <!-- Nếu là file PDF, sử dụng thẻ <a> để hiển thị -->
                                <a href="${e.message.file_path}" target="_blank" class="file-mess-link">
                                    <i class='bx bx-file'></i>
                                    <p>${e.message.content}</p>
                                </a>
                            ` : `
                                <!-- Các loại file khác, có thể chỉ hiển thị link tải về -->
                                <a href="${e.message.file_path}" download="${e.message.content}" class="file-mess-link">
                                    <i class='bx bx-file'></i>
                                    <p>${e.message.content}</p>
                                    
                                </a>
                            `}
                            <span>${e.message.created_at}</span>
                        `
                        }
                    </div>
                </div>
                 ${!e.message.is_deleted ? 
                    `<div class="other">
                        <button class="btn_other"><i class='bx bx-dots-vertical-rounded'></i></button>
                        <div class="popup-menu ${e.message.is_current_user ? 'user' : 'friend'}">
                            <ul>
                                ${userInRooms.map(userInRoom => {
                                    if (userCurrent == userInRoom.user_id && userInRoom.role_id == 2) {
                                        return `
                                            <li>
                                                <form action="${roleCurrent == 1 ? '/admin/deleteMess' : '/spacebox/deleteMess'}/${e.message.message_id}" method="post">
                                                    <input type="hidden" name="_token" value="${csrfToken}">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <button type="submit">Thu hồi</button>
                                                </form>
                                            </li>
                                        `;
                                    }
                                    return '';
                                }).join('')}
                                <li>
                                    <form action="#">
                                        <input type="hidden" name="_token" value="${csrfToken}">
                                        
                                        <button type="submit">Chuyển tiếp</button>
                                    </form>
                                </li>
                                ${e.message.is_pinned ? 
                                    `<li>
                                        <form action="/spacebox/unpinMess/${e.message.message_id}" method="post">
                                            <input type="hidden" name="_token" value="${csrfToken}">
                                            <input type="hidden" name="_method" value="PATCH">
                                            <button type="submit">Bỏ ghim</button>
                                        </form>
                                    </li>` : 
                                    `<li>
                                        <form action="${roleCurrent == 1 ? '/admin/pinMess' : '/spacebox/pinMess'}/${e.message.message_id}" method="post">
                                            <input type="hidden" name="_token" value="${csrfToken}">
                                            <input type="hidden" name="_method" value="PATCH">
                                            <button type="submit">Ghim</button>
                                        </form>
                                    </li>`
                                }
                            </ul>
                        </div>
                    </div>` : ''
                }
            </div>
        `;

        chatBox.insertAdjacentHTML('beforeend', messageHTML);

        const messageContainer = document.querySelector('.chat-content');
        messageContainer.scrollTop = messageContainer.scrollHeight;

        btnOther();
    });
}

function btnOther(){
    document.querySelectorAll('.btn_other').forEach(button => {
        button.addEventListener('click', function (e) {
            const popupMenu = this.nextElementSibling; // Popup ngay sau nút
            const isVisible = popupMenu.style.display === 'block';

            // Ẩn tất cả các popup khác trước khi mở popup mới
            document.querySelectorAll('.popup-menu').forEach(menu => menu.style.display = 'none');

            // Hiển thị/Ẩn popup
            popupMenu.style.display = isVisible ? 'none' : 'block';

            // Ngăn không cho sự kiện click lan ra ngoài
            e.stopPropagation();
        });
    });
}
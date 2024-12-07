import './bootstrap';

window.Echo.channel(`room.${roomId}`)
    .listen('.user.added', (e) => {
        const newUser = e.data.new_user;

        // Thêm thành viên mới vào danh sách
        const memberList = document.querySelector('.members-list');
        const newMember = document.createElement('li');
        newMember.classList.add('member');
        newMember.innerHTML = `
            <div class="member-right">
                <img src="${newUser.img_path}" alt="${newUser.username}" class="member-avatar">
                <div class="member-info">
                    <h4>${newUser.username}</h4>
                    <p>${newUser.email}</p>
                </div>
            </div>
            <span style="color: ${['red', 'aqua'][newUser.role_id - 2] ?? 'Unknown'}">
                ${['Moderate', 'Normal'][newUser.role_id - 2] ?? 'Unknown'}
            </span>
        `;
        memberList.appendChild(newMember);

        // Hiển thị thông báo
        alert(`${newUser.username} đã được thêm vào nhóm!`);
    });

document.addEventListener('DOMContentLoaded', () => {
    const authContainer = document.getElementById('auth-container');
    const chatContainer = document.getElementById('chat-container');
    const profileUsername = document.getElementById('profile-username');
    const logoutButton = document.getElementById('logout-button');
    const messageForm = document.getElementById('message-form');
    const messageInput = document.getElementById('message-input');
    const messagesList = document.getElementById('messages');
    const loginForm = document.getElementById('login-form');

    function loadMessages() {
        fetch('../get_messages.php')
            .then(response => response.json())
            .then(data => {
                messagesList.innerHTML = '';
                data.forEach(message => {
                    const li = document.createElement('li');
                    li.textContent = message;
                    messagesList.appendChild(li);
                });
            });
    }

    function checkAuth() {
        fetch('../check_auth.php')
            .then(response => response.json())
            .then(data => {
                if (data.authenticated) {
                    authContainer.style.display = 'none';
                    chatContainer.style.display = 'block';
                    profileUsername.textContent = data.username;
                    loadMessages();
                } else {
                    authContainer.style.display = 'block';
                    chatContainer.style.display = 'none';
                }
            });
    }

    checkAuth();

    loginForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const username = document.getElementById('username').value;
        const password = document.getElementById('password').value;

        fetch('../login.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ username, password })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                checkAuth();
            } else {
                alert(data.message);
            }
        });
    });

    messageForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const message = messageInput.value;

        fetch('../save_message.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ message })
        })
        .then(response => response.json())
        .then(data => {
            const li = document.createElement('li');
            li.textContent = data.message;
            messagesList.appendChild(li);
            messageInput.value = '';
        });
    });

    logoutButton.addEventListener('click', () => {
        fetch('../logout.php')
            .then(() => {
                checkAuth();
            });
    });
});

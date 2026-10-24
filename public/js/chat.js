const messages = document.querySelector('#messages');
const status = document.querySelector('#status');
const form = document.querySelector('#chat-form');
const input = document.querySelector('#message-input');
const sendButton = document.querySelector('#send-button');
const createButton = document.querySelector('#create-button');

function appendMessage(role, text) {
    const item = document.createElement('div');
    item.className = `message ${role}`;
    item.textContent = text;
    messages.appendChild(item);
    messages.scrollTop = messages.scrollHeight;
}

async function sendMessage(message) {
    sendButton.disabled = true;
    input.disabled = true;
    status.textContent = 'AI đang xử lý...';

    try {
        const response = await fetch('../api/chat.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ message })
        });
        const result = await response.json();
        if (!response.ok || !result.success) {
            throw new Error(result.message || 'Không thể kết nối Chat AI.');
        }

        appendMessage('assistant', result.data.reply);
        if (result.data.is_complete) {
            createButton.style.display = 'inline-block';
            input.disabled = true;
        }
        status.textContent = '';
    } catch (error) {
        status.textContent = error.message;
    } finally {
        sendButton.disabled = false;
        if (createButton.style.display !== 'inline-block') {
            input.disabled = false;
        }
        input.focus();
    }
}

form.addEventListener('submit', event => {
    event.preventDefault();
    const message = input.value.trim();
    if (message === '') {
        status.textContent = 'Bạn hãy nhập câu trả lời.';
        return;
    }
    appendMessage('user', message);
    input.value = '';
    sendMessage(message);
});

createButton.addEventListener('click', async () => {
    createButton.disabled = true;
    status.textContent = 'Đang tạo CV...';
    try {
        const response = await fetch('../api/create-cv.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({})
        });
        const result = await response.json();
        if (!response.ok || !result.success) {
            throw new Error(result.message || 'Không thể tạo CV.');
        }
        window.location.href = `preview.php?cv_id=${result.data.cv_id}`;
    } catch (error) {
        createButton.disabled = false;
        status.textContent = error.message;
    }
});

sendMessage('');

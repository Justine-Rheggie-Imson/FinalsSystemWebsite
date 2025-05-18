// Chat functionality
let currentChatUser = null;
let chatInterval = null;

// Define sendMessage function
function sendMessage(e) {
    // Prevent any default behavior
    if (e) {
        e.preventDefault();
        e.stopPropagation();
    }
    
    console.log('sendMessage function called');
    
    const chatForm = document.getElementById('chatForm');
    if (!chatForm) {
        console.error('Chat form not found');
        return false;
    }

    const messageInput = chatForm.querySelector('input');
    if (!messageInput) {
        console.error('Message input not found');
        return false;
    }

    const message = messageInput.value.trim();
    console.log('Message:', message);
    
    if (!message) {
        console.log('Empty message, returning');
        return false;
    }

    const currentDoctorId = chatForm.dataset.doctorId;
    console.log('Current doctor ID:', currentDoctorId);
    
    if (!currentDoctorId) {
        console.error('No doctor selected');
        alert('Please select a doctor to chat with');
        return false;
    }

    // Get CSRF token
    const csrfToken = document.getElementById('csrfToken')?.value;
    console.log('CSRF token present:', !!csrfToken);

    if (!csrfToken) {
        console.error('CSRF token not found');
        alert('Security token missing. Please refresh the page and try again.');
        return false;
    }

    // Send message
    console.log('Sending message to server...');
    fetch('/FinalsWeb/functions/clientFunction/send_message.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            receiver_id: currentDoctorId,
            message: message,
            csrf_token: csrfToken
        })
    })
    .then(response => {
        console.log('Server response status:', response.status);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(result => {
        console.log('Server response:', result);
        if (result.success) {
            console.log('Message sent successfully');
            // Clear input
            messageInput.value = '';
            
            // Reload chat
            loadChat(currentDoctorId);
        } else {
            console.error('Error sending message:', result.message);
            alert('Failed to send message: ' + (result.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error in fetch:', error);
        alert('Failed to send message. Please try again. Error: ' + error.message);
    });

    // Prevent form submission
    return false;
}

// Initialize chat functionality
function initChat() {
    console.log('Initializing chat...');
    const chatForm = document.getElementById('chatForm');
    const chatMessages = document.querySelector('.chat-messages');

    // Only initialize if we have the necessary elements
    if (!chatForm || !chatMessages) {
        console.error('Chat elements not found:', {
            chatForm: !!chatForm,
            chatMessages: !!chatMessages
        });
        return;
    }

    console.log('Chat elements found, chat initialized');
}

// Load chat with a doctor
function loadChat(doctorId) {
    console.log('Loading chat for doctor:', doctorId);
    
    const chatMessages = document.querySelector('.chat-messages');
    const chatForm = document.getElementById('chatForm');
    
    if (!chatMessages || !chatForm) {
        console.error('Chat elements not found:', {
            chatMessages: !!chatMessages,
            chatForm: !!chatForm
        });
        return;
    }

    chatForm.style.display = 'block';
    chatForm.dataset.doctorId = doctorId;
    
    // Update active state in doctor list
    document.querySelectorAll('#chatDoctorList .list-group-item').forEach(item => {
        item.classList.remove('active');
        if (item.onclick.toString().includes(doctorId)) {
            item.classList.add('active');
        }
    });
    
    console.log('Fetching chat messages...');
    fetch(`/FinalsWeb/functions/clientFunction/get_chat.php?doctor_id=${doctorId}`)
        .then(response => {
            console.log('Chat fetch response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Chat data received:', data);
            if (data.success) {
                chatMessages.innerHTML = data.messages.map(msg => `
                    <div class="message ${msg.message_type}">
                        <div class="message-content ${msg.message_type === 'sent' ? 'bg-primary text-white' : 'bg-light'} p-2 rounded mb-2">
                            ${msg.message}
                        </div>
                        <small class="text-muted">${new Date(msg.created_at).toLocaleString()}</small>
                    </div>
                `).join('');
                chatMessages.scrollTop = chatMessages.scrollHeight;
            } else {
                console.error('Failed to load chat messages:', data.message);
                chatMessages.innerHTML = `<div class="alert alert-danger">Failed to load chat messages: ${data.message || 'Unknown error'}</div>`;
            }
        })
        .catch(error => {
            console.error('Error loading chat:', error);
            chatMessages.innerHTML = `<div class="alert alert-danger">Failed to load chat messages: ${error.message}</div>`;
        });
}

// Initialize chat when document is ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('Document ready, initializing chat...');
    initChat();
}); 
function showSection(sectionId) {
  // Hide all sections
  document.querySelectorAll('.content-section').forEach(section => {
    section.classList.remove('active');
  });

  // Show selected section
  document.getElementById(sectionId).classList.add('active');

  // Navbar content switching
  const navbarTitle = document.getElementById('navbarTitle');
  const navbarRight = document.getElementById('navbarRight');

  if (sectionId === 'appointments') {
    
    navbarRight.innerHTML = ""; // No settings icon needed here
  } else if (sectionId === 'files') {
    initializeFiles();
  } else if (sectionId === 'messages') {
    initializeChat();
  } else {
    navbarTitle.textContent = "Welcome, Justine";
    navbarRight.innerHTML = `
      <a href="editClientInfo.php" class="text-decoration-none text-dark">
        <i class="bi bi-gear-fill fs-4" title="Settings"></i>
      </a>
    `;
  }
}

function loadAppointmentContent(type) {
  const upcomingTab = document.getElementById('upcoming');
  const historyTab = document.getElementById('history');
  
  if (type === 'current') {
    if (upcomingTab) upcomingTab.classList.add('show', 'active');
    if (historyTab) historyTab.classList.remove('show', 'active');
  } else {
    if (upcomingTab) upcomingTab.classList.remove('show', 'active');
    if (historyTab) historyTab.classList.add('show', 'active');
  }
}

function initializeChat() {
  // Load doctor list
  fetch('../functions/clientFunction/get_doctors.php')
    .then(response => {
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      return response.json();
    })
    .then(data => {
      console.log('Doctor data received:', data);
      if (data.success) {
        const doctorList = document.getElementById('chatDoctorList');
        if (!doctorList) {
          console.error('Chat doctor list element not found');
          return;
        }
        doctorList.innerHTML = data.doctors.map(doctor => `
          <a href="#" class="list-group-item list-group-item-action" onclick="loadChat(${doctor.id})">
            <div class="d-flex align-items-center">
              <img src="${doctor.image || '/FinalsWeb/img/default-doctor.png'}" class="rounded-circle me-2" style="width: 40px; height: 40px; object-fit: cover;">
              <div>
                <h6 class="mb-0">${doctor.name}</h6>
                <small class="text-muted">${doctor.unread_count > 0 ? `${doctor.unread_count} unread messages` : 'No new messages'}</small>
              </div>
            </div>
          </a>
        `).join('');
      } else {
        console.error('Failed to load doctors:', data.message);
        document.getElementById('chatDoctorList').innerHTML = `<div class="alert alert-danger">${data.message || 'Failed to load doctors'}</div>`;
      }
    })
    .catch(error => {
      console.error('Error loading doctors:', error);
      const doctorList = document.getElementById('chatDoctorList');
      if (doctorList) {
        doctorList.innerHTML = `<div class="alert alert-danger">Failed to load doctors: ${error.message}</div>`;
      }
    });

  // Initialize chat form
  const chatForm = document.getElementById('chatForm');
  if (chatForm) {
    // Remove any existing event listeners
    const newChatForm = chatForm.cloneNode(true);
    chatForm.parentNode.replaceChild(newChatForm, chatForm);
    
    newChatForm.addEventListener('submit', function(e) {
      console.log('Chat form submit event triggered');
      e.preventDefault();
      const messageInput = this.querySelector('input');
      if (!messageInput) {
        console.error('Message input not found');
        return;
      }
      const message = messageInput.value.trim();
      if (!message) return;

      const currentDoctorId = this.dataset.doctorId;
      if (!currentDoctorId) {
        console.error('No doctor selected');
        return;
      }

      // Get CSRF token
      const csrfTokenElem = document.getElementById('csrfToken');
      if (!csrfTokenElem) {
        console.error('CSRF token not found');
        return;
      }
      const csrfToken = csrfTokenElem.value;

      // Store chat state in case of refresh
      localStorage.setItem('lastChatSection', 'messages');
      localStorage.setItem('lastChatDoctorId', currentDoctorId);

      // Send message
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
      .then(response => response.json())
      .then(result => {
        if (result.success) {
          // Clear input
          messageInput.value = '';
          // Reload chat
          loadChat(currentDoctorId);
        } else {
          console.error('Error sending message:', result.message);
          alert('Failed to send message: ' + result.message);
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('Failed to send message. Please try again.');
      });
    });
  }
}

function loadChat(doctorId) {
  const chatMessages = document.querySelector('.chat-messages');
  const chatForm = document.getElementById('chatForm');
  chatForm.style.display = 'block';
  chatForm.dataset.doctorId = doctorId;
  
  // Update active state in doctor list
  document.querySelectorAll('#chatDoctorList .list-group-item').forEach(item => {
    item.classList.remove('active');
    if (item.onclick.toString().includes(doctorId)) {
      item.classList.add('active');
    }
  });
  
  fetch(`/FinalsWeb/functions/clientFunction/get_chat.php?doctor_id=${doctorId}`)
    .then(response => response.json())
    .then(data => {
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
        chatMessages.innerHTML = '<div class="alert alert-danger">Failed to load chat messages</div>';
      }
    })
    .catch(error => {
      console.error('Error loading chat:', error);
      chatMessages.innerHTML = '<div class="alert alert-danger">Failed to load chat messages</div>';
    });
}

// Initialize chat when messages section is shown
document.addEventListener('DOMContentLoaded', () => {
  const messagesSection = document.getElementById('messages');
  if (messagesSection) {
    const observer = new MutationObserver((mutations) => {
      mutations.forEach((mutation) => {
        if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
          if (messagesSection.classList.contains('active')) {
            initializeChat();
          }
        }
      });
    });
    
    observer.observe(messagesSection, { attributes: true });
  }
});

function openRescheduleModal(appointmentId) {
  document.getElementById('rescheduleAppointmentId').value = appointmentId;
  var modal = new bootstrap.Modal(document.getElementById('rescheduleModal'));
  modal.show();
}

document.addEventListener('DOMContentLoaded', function() {
  const rescheduleForm = document.getElementById('rescheduleForm');
  if (rescheduleForm) {
    rescheduleForm.addEventListener('submit', function(e) {
      e.preventDefault();
      const appointmentId = document.getElementById('rescheduleAppointmentId').value;
      const new_date = this.new_date.value;
      const new_time = this.new_time.value;
      fetch('../functions/clientFunction/update_appointment.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({
          appointment_id: appointmentId,
          new_date: new_date,
          new_time: new_time,
          csrf_token: csrfToken
        })
      })
      .then(res => res.json())
      .then(data => {
        alert(data.message);
        location.reload();
      });
    });
  }
});

function cancelAppointment(appointmentId) {
  if (!confirm('Are you sure you want to cancel this appointment?')) return;
  fetch('../functions/clientFunction/update_appointment.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify({
      appointment_id: appointmentId,
      status: 'cancelled',
      csrf_token: csrfToken
    })
  })
  .then(res => res.json())
  .then(data => {
    alert(data.message);
    location.reload();
  });
}

function respondReschedule(appointmentId, action) {
  fetch('../functions/clientFunction/update_appointment.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify({
      appointment_id: appointmentId,
      reschedule_action: action,
      csrf_token: csrfToken
    })
  })
  .then(res => res.json())
  .then(data => {
    alert(data.message);
    location.reload();
  });
}

document.addEventListener('DOMContentLoaded', function() {
  // Restore chat section and doctor if a refresh was caused by sending a message
  const lastSection = localStorage.getItem('lastChatSection');
  const lastDoctorId = localStorage.getItem('lastChatDoctorId');
  if (lastSection === 'messages') {
    showSection('messages');
    if (lastDoctorId) {
      setTimeout(() => {
        loadChat(lastDoctorId);
      }, 500);
    }
    // Clear after restoring
    localStorage.removeItem('lastChatSection');
    localStorage.removeItem('lastChatDoctorId');
  }
});


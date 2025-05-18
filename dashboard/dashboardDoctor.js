//// Global variables

let doctorId;
let csrfToken;

// --- Doctor File System Logic (match client dashboard) ---
let currentFilePatient = null;

// Function to show a section
function showSection(sectionId) {
    console.log('Showing section:', sectionId);
    
    // Hide all sections first
    document.querySelectorAll('.content-section').forEach(section => {
        section.classList.remove('active');
        console.log('Hiding section:', section.id);
    });
    
    // Show selected section
    const selectedSection = document.getElementById(sectionId);
    if (selectedSection) {
        selectedSection.classList.add('active');
        console.log('Section shown:', sectionId);
        
        // Initialize section-specific elements
        if (sectionId === 'chat') {
            initializeChat();
        } else if (sectionId === 'editProfile') {
            initializeFormHandlers();
        }
    } else {
        console.error('Section not found:', sectionId);
    }
    
    // Update navigation active state
    document.querySelectorAll('.nav-link').forEach(link => {
        link.classList.remove('active');
    });
    const activeLink = document.querySelector(`[onclick="showSection('${sectionId}')"]`);
    if (activeLink) {
        activeLink.classList.add('active');
    }

    // Update navbar content
    const navbarTitle = document.getElementById('navbarTitle');
    const navbarRight = document.getElementById('navbarRight');

    switch(sectionId) {
        case 'appointments':
            navbarTitle.textContent = "Appointments";
            navbarRight.innerHTML = "";
            break;
        case 'chat':
            navbarTitle.textContent = "Chat with Patients";
            navbarRight.innerHTML = "";
            break;
        case 'viewProfile':
        case 'editProfile':
            navbarTitle.textContent = sectionId === 'viewProfile' ? "View Profile" : "Edit Profile";
            navbarRight.innerHTML = `
                <a href="#" class="text-decoration-none text-dark" onclick="showSection('${sectionId === 'viewProfile' ? 'editProfile' : 'viewProfile'}')">
                    <i class="bi bi-${sectionId === 'viewProfile' ? 'pencil' : 'person'} fs-4" title="${sectionId === 'viewProfile' ? 'Edit Profile' : 'View Profile'}"></i>
                </a>
            `;
            break;
        default:
            navbarTitle.textContent = "Welcome, Doctor";
            navbarRight.innerHTML = `
                <a href="#" class="text-decoration-none text-dark" onclick="showSection('editProfile')">
                    <i class="bi bi-gear-fill fs-4" title="Settings"></i>
                </a>
            `;
    }
}

function handleFormSuccess(data) {
    if (data.success) {
        // Show success message
        const successMessage = document.querySelector('#editProfileForm .success-message');
        if (successMessage) {
            successMessage.textContent = data.message;
            successMessage.style.display = 'block';
        }

        // Update view profile section
        const viewProfile = document.getElementById('viewProfile');
        if (viewProfile) {
            // Update profile image if changed
            if (data.image) {
                const profileImage = viewProfile.querySelector('img');
                if (profileImage) {
                    profileImage.src = data.image;
                }
            }

            // Update text fields
            Object.keys(data).forEach(key => {
                if (key !== 'success' && key !== 'message' && key !== 'image') {
                    const element = viewProfile.querySelector(`[data-field="${key}"]`);
                    if (element) {
                        element.textContent = data[key];
                    }
                }
            });

            // Update availability
            if (data.in_person !== undefined || data.online !== undefined) {
                const availabilityElement = viewProfile.querySelector('[data-field="availability"]');
                if (availabilityElement) {
                    const availability = [];
                    if (data.in_person === 1) availability.push('In-Person');
                    if (data.online === 1) availability.push('Online');
                    availabilityElement.textContent = availability.length > 0 ? availability.join(' / ') : 'N/A';
                }
            }
        }

        // Clear form
        const form = document.getElementById('editProfileForm');
        if (form) {
            form.reset();
        }
    } else {
        // Show error message
        const errorMessage = document.querySelector('#editProfileForm .error-message');
        if (errorMessage) {
            errorMessage.textContent = data.message;
            errorMessage.style.display = 'block';
        }
    }
}

function initializeFormHandlers() {
    console.log('Initializing form handlers...');
    const editProfileForm = document.getElementById('editProfileForm');
    if (editProfileForm) {
        console.log('Edit profile form found');
        editProfileForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            console.log('Form submitted');
            
            if (!editProfileForm.checkValidity()) {
                e.stopPropagation();
                editProfileForm.classList.add('was-validated');
                return;
            }

            const submitButton = editProfileForm.querySelector('button[type="submit"]');
            const loadingSpinner = submitButton.querySelector('.loading');
            const errorMessage = editProfileForm.querySelector('.error-message');
            const successMessage = editProfileForm.querySelector('.success-message');

            try {
                submitButton.disabled = true;
                loadingSpinner.classList.add('active');
                errorMessage.style.display = 'none';
                successMessage.style.display = 'none';

                // Create FormData object
                const formData = new FormData(editProfileForm);
                
                // Log form data for debugging
                console.log('Form data being sent:');
                for (let [key, value] of formData.entries()) {
                    console.log(key + ': ' + value);
                }

                const response = await fetch(editProfileForm.action, {
                    method: 'POST',
                    body: formData
                });

                // Log response status
                console.log('Response status:', response.status);

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const result = await response.json();
                console.log('Server response:', result);

                if (result.success) {
                    successMessage.textContent = result.message || 'Profile updated successfully!';
                    successMessage.className = 'success-message mt-3 alert alert-success';
                    successMessage.style.display = 'block';
                    
                    // Reload the page after 2 seconds to show updated data
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                } else {
                    errorMessage.textContent = result.message || 'Error updating profile';
                    errorMessage.className = 'error-message mt-3 alert alert-danger';
                    errorMessage.style.display = 'block';
                }
            } catch (error) {
                console.error('Error:', error);
                errorMessage.textContent = `Error: ${error.message}. Please check the console for more details.`;
                errorMessage.className = 'error-message mt-3 alert alert-danger';
                errorMessage.style.display = 'block';
            } finally {
                submitButton.disabled = false;
                loadingSpinner.classList.remove('active');
            }
        });
    } else {
        console.error('Edit profile form not found');
    }
}

function initializeFileValidation() {
    document.querySelectorAll('input[type="file"]').forEach(input => {
        input.addEventListener('change', function(e) {
            const maxSize = parseInt(this.dataset.maxSize);
            if (this.files[0] && this.files[0].size > maxSize) {
                this.value = '';
                const errorMessage = this.closest('form').querySelector('.error-message');
                if (errorMessage) {
                    errorMessage.textContent = 'File size exceeds the maximum limit of 5MB';
                    errorMessage.style.display = 'block';
                }
            }
        });
    });
}

function initializeChat() {
    // Only set up the chat form event handler; do not fetch or render chatPatientList
    const chatForm = document.getElementById('chatForm');
    if (chatForm) {
        // Remove any existing event listeners
        const newChatForm = chatForm.cloneNode(true);
        chatForm.parentNode.replaceChild(newChatForm, chatForm);
        newChatForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const messageInput = this.querySelector('input');
            if (!messageInput) {
                console.error('Message input not found');
                return;
            }
            const message = messageInput.value.trim();
            if (!message) return;

            const currentClientId = this.dataset.clientId;
            if (!currentClientId) {
                console.error('No client selected');
                alert('Please select a client to chat with');
                return;
            }

            // Get CSRF token
            const csrfTokenElem = document.getElementById('csrfToken');
            if (!csrfTokenElem) {
                console.error('CSRF token not found');
                alert('Security token missing. Please refresh the page and try again.');
                return;
            }
            const csrfToken = csrfTokenElem.value;

            // Send message
            fetch('../functions/doctorFunction/send_message.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    receiver_id: currentClientId,
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
                    loadChat(currentClientId);
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

function loadChat(clientId) {

    const chatMessages = document.querySelector('.chat-messages');
    const chatForm = document.getElementById('chatForm');
    if (!chatMessages || !chatForm) {
        console.error('Chat elements not found:', {
            chatMessages: !!chatMessages,
            chatForm: !!chatForm
        });
        return;
    }
    if (!clientId || isNaN(clientId) || clientId <= 0) {
        console.error('Invalid or missing clientId:', clientId);
        chatMessages.innerHTML = '<div class="alert alert-danger">Invalid or missing client selected. Please select a valid client from the list.</div>';
        return;
    }
    chatForm.style.display = 'block';
    chatForm.dataset.clientId = clientId;

    // Update active state in client list
    document.querySelectorAll('#chatClientList .list-group-item').forEach(item => {
        item.classList.remove('active');
        if (item.onclick.toString().includes(clientId)) {
            item.classList.add('active');
        }
    });

    console.log('Fetching chat messages for clientId:', clientId);
    const url = `/FinalsWeb/functions/doctorFunction/get_chat.php?client_id=${clientId}`;
    console.log('Chat fetch URL:', url);
    fetch(url)
        .then(response => {
            console.log('Chat fetch response status:', response.status);
            if (!response.ok) {
                console.error('Fetch response not OK:', response.status, response.statusText);
            }
            return response.text().then(text => {
                let data;
                try {
                    data = JSON.parse(text);
                } catch (e) {
                    console.error('Failed to parse JSON:', text);
                    chatMessages.innerHTML = `<div class=\"alert alert-danger\">Server returned invalid JSON: <pre>${text}</pre></div>`;
                    return;
                }
                
                console.log('Raw server response:', text);
                console.log('Parsed chat data:', data);
                if (data.success) {
                    chatMessages.innerHTML = data.messages.map(msg => `
                        <div class=\"message ${msg.message_type}\">
                            <div class=\"message-content ${msg.message_type === 'sent' ? 'bg-primary text-white' : 'bg-light'} p-2 rounded mb-2\">
                                ${msg.message}
                            </div>
                            <small class=\"text-muted\">${new Date(msg.created_at).toLocaleString()}</small>
                        </div>
                    `).join('');
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                } else {
                    console.error('Failed to load chat messages:', data.message, 'clientId:', clientId, 'URL:', url, 'Raw:', text);
                    chatMessages.innerHTML = `<div class=\"alert alert-danger\">Failed to load chat messages: ${data.message || 'Unknown error'}<br>clientId: ${clientId}<br>URL: ${url}<br>Raw: <pre>${text}</pre></div>`;
                }
            });
        })
        .catch(error => {
            console.error('Error loading chat:', error, 'clientId:', clientId, 'URL:', url);
            chatMessages.innerHTML = `<div class=\"alert alert-danger\">Failed to load chat messages: ${error.message}<br>clientId: ${clientId}<br>URL: ${url}</div>`;
        });
}

function initializeAppointmentHandlers() {
    window.viewAppointment = function(appointmentId) {
        // Implement appointment details view
        console.log('Viewing appointment:', appointmentId);
    }

    window.updateAppointmentStatus = function(appointmentId, status) {
        if (!confirm(`Are you sure you want to ${status} this appointment?`)) {
            return;
        }

        fetch('/FinalsWeb/functions/doctorFunction/update_appointment.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                appointment_id: appointmentId,
                status: status,
                csrf_token: csrfToken
            })
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                location.reload();
            } else {
                alert(result.message || 'Failed to update appointment status');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while updating the appointment status');
        });
    }
}

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
            fetch('/FinalsWeb/functions/doctorFunction/update_appointment.php', {
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
    fetch('/FinalsWeb/functions/doctorFunction/update_appointment.php', {
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
    fetch('/FinalsWeb/functions/doctorFunction/update_appointment.php', {
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

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing...');
    
    // Get global variables
    doctorId = document.getElementById('doctorId').value;
    csrfToken = document.getElementById('csrfToken').value;
    
    console.log('Doctor ID:', doctorId);
    console.log('CSRF Token:', csrfToken);
    
    // Initialize appointment handlers
    initializeAppointmentHandlers();
    
    // Show default section
    showSection('appointments');

    // Handle edit buttons
    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function() {
            const field = this.dataset.field;
            const inputGroup = this.closest('.mb-3').querySelector('.input-group');
            const input = inputGroup.querySelector('input, textarea');
            const saveBtn = inputGroup.querySelector('.save-btn');
            const cancelBtn = inputGroup.querySelector('.cancel-btn');
            
            // Enable the input
            if (input.type === 'file') {
                input.disabled = false;
            } else if (input.type === 'checkbox') {
                input.disabled = false;
            } else {
                input.readOnly = false;
            }
            
            // Show save and cancel buttons
            saveBtn.style.display = 'block';
            cancelBtn.style.display = 'block';
            
            // Hide edit button
            this.style.display = 'none';
        });
    });

    // Handle save buttons
    document.querySelectorAll('.save-btn').forEach(button => {
        button.addEventListener('click', function() {
            const field = this.dataset.field;
            const inputGroup = this.closest('.input-group');
            const input = inputGroup.querySelector('input, textarea');
            const editBtn = this.closest('.mb-3').querySelector('.edit-btn');
            
            // Create form data
            const formData = new FormData();
            formData.append('field', field);
            formData.append('value', input.type === 'checkbox' ? input.checked ? '1' : '0' : input.value);
            formData.append('csrf_token', document.getElementById('csrfToken').value);

            // Send AJAX request
            fetch('../functions/doctorFunction/update_profile.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    const successMessage = document.querySelector('.success-message');
                    successMessage.textContent = 'Profile updated successfully!';
                    successMessage.className = 'success-message mt-3 alert alert-success';
                    
                    // Disable input and hide buttons
                    if (input.type === 'file') {
                        input.disabled = true;
                    } else if (input.type === 'checkbox') {
                        input.disabled = true;
                    } else {
                        input.readOnly = true;
                    }
                    this.style.display = 'none';
                    inputGroup.querySelector('.cancel-btn').style.display = 'none';
                    editBtn.style.display = 'block';
                } else {
                    // Show error message
                    const errorMessage = document.querySelector('.error-message');
                    errorMessage.textContent = data.message || 'Error updating profile';
                    errorMessage.className = 'error-message mt-3 alert alert-danger';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                const errorMessage = document.querySelector('.error-message');
                errorMessage.textContent = 'An error occurred while updating the profile';
                errorMessage.className = 'error-message mt-3 alert alert-danger';
            });
        });
    });

    // Handle cancel buttons
    document.querySelectorAll('.cancel-btn').forEach(button => {
        button.addEventListener('click', function() {
            const field = this.dataset.field;
            const inputGroup = this.closest('.input-group');
            const input = inputGroup.querySelector('input, textarea');
            const editBtn = this.closest('.mb-3').querySelector('.edit-btn');
            
            // Reset input value to original
            if (input.type === 'checkbox') {
                input.checked = input.defaultChecked;
                input.disabled = true;
            } else if (input.type === 'file') {
                input.value = '';
                input.disabled = true;
            } else {
                input.value = input.defaultValue;
                input.readOnly = true;
            }
            
            // Hide save and cancel buttons
            this.style.display = 'none';
            inputGroup.querySelector('.save-btn').style.display = 'none';
            
            // Show edit button
            editBtn.style.display = 'block';
        });
    });

    initializeDoctorFiles();
});

// Initialize chat when chat section is shown
// (patch showSection to call initializeChat when chat is shown)
const origShowSectionDoctor = window.showSection;
window.showSection = function(sectionId) {
    origShowSectionDoctor(sectionId);
    if (sectionId === 'chat') {
        initializeChat();
    }
};

window.loadChat = loadChat;

function selectPatient(patientId, patientName) {
    console.log('Selecting patient:', patientId, patientName);
    currentFilePatient = patientId;
    const fileList = document.getElementById('fileList');
    const fileUploadForm = document.getElementById('fileUploadForm');
    const selectedPatientSpan = document.getElementById('selectedPatient');
    
    // Update selected patient name
    if (selectedPatientSpan) {
        selectedPatientSpan.textContent = patientName;
    }
    
    // Update active state in patient list
    document.querySelectorAll('#filePatientList .list-group-item').forEach(item => {
        item.classList.remove('active');
        if (item.onclick.toString().includes(patientId)) {
            item.classList.add('active');
        }
    });
    
    // Show file upload form
    if (fileUploadForm) {
        fileUploadForm.style.display = 'block';
    }
    
    // Load shared files
    console.log('Loading shared files for patient:', patientId);
    fetch(`/FinalsWeb/functions/doctorFunction/get_shared_files.php?patient_id=${patientId}`)
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Parsed data:', data);
            if (data.success) {
                if (data.files && data.files.length > 0) {
                    displayFiles(data.files);
                } else {
                    fileList.innerHTML = '<div class="text-center text-muted">No files shared yet</div>';
                }
            } else {
                console.error('Failed to load files:', data.message);
                fileList.innerHTML = `<div class="alert alert-danger">Failed to load shared files: ${data.message}</div>`;
            }
        })
        .catch(error => {
            console.error('Error loading files:', error);
            fileList.innerHTML = `<div class="alert alert-danger">Failed to load shared files: ${error.message}</div>`;
        });
}

function displayFiles(files) {
    console.log('Displaying files:', files);
    const fileList = document.getElementById('fileList');
    if (!fileList) {
        console.error('File list element not found');
        return;
    }
    if (files.length === 0) {
        fileList.innerHTML = '<div class="text-center text-muted">No files shared yet</div>';
        return;
    }
    fileList.innerHTML = files.map(file => `
        <div class="list-group-item">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-0">${file.filename}</h6>
                    <small class="text-muted">
                        Shared by ${file.sender_name} on ${new Date(file.upload_date).toLocaleString()}
                    </small>
                </div>
                <div>
                    <button onclick="downloadFile(${file.id}, '${file.filename}')" class="btn btn-sm btn-primary">
                        <i class="bi bi-download"></i> Download
                    </button>
                </div>
            </div>
        </div>
    `).join('');
    console.log('Files displayed');
}

async function downloadFile(fileId, filename) {
    try {
        console.log('Starting download for file:', fileId);
        const response = await fetch(`/FinalsWeb/functions/doctorFunction/download_shared_file.php?id=${fileId}`);
        
        if (!response.ok) {
            const errorText = await response.text();
            console.error('Download failed:', response.status, errorText);
            showAlert(`Download failed: ${errorText}`, 'danger');
            return;
        }

        // Get the blob from the response
        const blob = await response.blob();
        
        // Create a temporary URL for the blob
        const url = window.URL.createObjectURL(blob);
        
        // Create a temporary link element
        const link = document.createElement('a');
        link.href = url;
        link.download = filename;
        
        // Append to body, click, and remove
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        
        // Clean up the URL
        window.URL.revokeObjectURL(url);
        
        console.log('Download completed successfully');
    } catch (error) {
        console.error('Error downloading file:', error);
        showAlert('Failed to download file. Please try again.', 'danger');
    }
}

function uploadFile(formData) {
    console.log('Uploading file...');
    fetch('/FinalsWeb/functions/doctorFunction/upload_file.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        console.log('Upload response:', data);
        if (data.success) {
            showAlert('File uploaded successfully', 'success');
            document.getElementById('fileUploadForm').reset();
            selectPatient(currentFilePatient, document.getElementById('selectedPatient').textContent); // Reload files
        } else {
            showAlert(data.message || 'Failed to upload file', 'danger');
        }
    })
    .catch(error => {
        console.error('Error uploading file:', error);
        showAlert('Failed to upload file', 'danger');
    });
}

function showAlert(message, type) {
    console.log('Showing alert:', message, type);
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    document.querySelector('.container').insertBefore(alertDiv, document.querySelector('.container').firstChild);
    setTimeout(() => alertDiv.remove(), 5000);
}

// Initialize file upload form for doctor
function initializeDoctorFiles() {
    const fileUploadForm = document.getElementById('fileUploadForm');
    if (fileUploadForm) {
        fileUploadForm.addEventListener('submit', function(e) {
            e.preventDefault();
            if (!currentFilePatient) {
                showAlert('Please select a patient first', 'warning');
                return;
            }
            const fileInput = this.querySelector('input[type="file"]');
            if (!fileInput.files.length) {
                showAlert('Please select a file to upload', 'warning');
                return;
            }
            const formData = new FormData(this);
            formData.append('patient_id', currentFilePatient);
            uploadFile(formData);
        });
    } else {
        console.error('File upload form not found');
    }
}

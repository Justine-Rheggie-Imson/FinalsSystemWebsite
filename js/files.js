// File sharing functionality
let currentFileDoctor = null;

// Initialize file sharing functionality
function initializeFiles() {
    console.log('Initializing file sharing...');
    
    // Load doctor list
    console.log('Fetching doctors from get_doctors.php...');
    fetch('/FinalsWeb/functions/clientFunction/get_doctors.php')
        .then(response => {
            console.log('Response received:', response);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Doctor data received:', data);
            if (data.success) {
                const doctorList = document.getElementById('doctorList');
                if (!doctorList) {
                    console.error('Doctor list element not found');
                    return;
                }
                console.log('Rendering doctor list...');
                doctorList.innerHTML = data.doctors.map(doctor => `
                    <a href="#" class="list-group-item list-group-item-action" onclick="selectDoctor(${doctor.id}, '${doctor.name}')">
                        <div class="d-flex align-items-center">
                            <img src="${doctor.image || '/FinalsWeb/img/default-doctor.png'}" class="rounded-circle me-2" style="width: 40px; height: 40px; object-fit: cover;">
                            <div>
                                <h6 class="mb-0">${doctor.name}</h6>
                                <small class="text-muted">${doctor.unread_count > 0 ? `${doctor.unread_count} unread messages` : 'No new messages'}</small>
                            </div>
                        </div>
                    </a>
                `).join('');
                console.log('Doctor list rendered');
            } else {
                console.error('Failed to load doctors:', data.message);
                document.getElementById('doctorList').innerHTML = `<div class="alert alert-danger">${data.message || 'Failed to load doctors'}</div>`;
            }
        })
        .catch(error => {
            console.error('Error loading doctors:', error);
            const doctorList = document.getElementById('doctorList');
            if (doctorList) {
                doctorList.innerHTML = `<div class="alert alert-danger">Failed to load doctors: ${error.message}</div>`;
            }
        });

    // Initialize file upload form
    const fileUploadForm = document.getElementById('fileUploadForm');
    if (fileUploadForm) {
        console.log('Initializing file upload form...');
        // Remove any existing event listeners
        const newFileUploadForm = fileUploadForm.cloneNode(true);
        fileUploadForm.parentNode.replaceChild(newFileUploadForm, fileUploadForm);
        
        newFileUploadForm.addEventListener('submit', function(e) {
            e.preventDefault();
            if (!currentFileDoctor) {
                showAlert('Please select a doctor first', 'warning');
                return;
            }

            const fileInput = this.querySelector('input[type="file"]');
            if (!fileInput.files.length) {
                showAlert('Please select a file to upload', 'warning');
                return;
            }

            const formData = new FormData(this);
            formData.append('doctor_id', currentFileDoctor);
            formData.append('csrf_token', document.getElementById('csrfToken').value);
            uploadFile(formData);
        });
        console.log('File upload form initialized');
    } else {
        console.error('File upload form not found');
    }
}

// Select doctor and load their files
function selectDoctor(doctorId, doctorName) {
    console.log('Selecting doctor:', doctorId, doctorName);
    currentFileDoctor = doctorId;
    const fileList = document.getElementById('fileList');
    const fileUploadForm = document.getElementById('fileUploadForm');
    const selectedDoctorSpan = document.getElementById('selectedDoctor');
    
    // Update selected doctor name
    if (selectedDoctorSpan) {
        selectedDoctorSpan.textContent = doctorName;
    }
    
    // Update active state in doctor list
    document.querySelectorAll('#doctorList .list-group-item').forEach(item => {
        item.classList.remove('active');
        if (item.onclick.toString().includes(doctorId)) {
            item.classList.add('active');
        }
    });
    
    // Show file upload form
    if (fileUploadForm) {
        fileUploadForm.style.display = 'block';
    }
    
    // Load shared files
    console.log('Loading shared files for doctor:', doctorId);
    fetch(`/FinalsWeb/functions/clientFunction/get_shared_files.php?doctor_id=${doctorId}`)
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

// Display shared files
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
                    <a href="/FinalsWeb/functions/clientFunction/download_shared_file.php?id=${file.id}" class="btn btn-sm btn-primary">
                        <i class="bi bi-download"></i> Download
                    </a>
                </div>
            </div>
        </div>
    `).join('');
    console.log('Files displayed');
}

// Upload file
function uploadFile(formData) {
    console.log('Uploading file...', {
        doctor_id: formData.get('doctor_id'),
        file: formData.get('file'),
        csrf_token: formData.get('csrf_token')
    });

    fetch('/FinalsWeb/functions/clientFunction/upload_file.php', {
        method: 'POST',
        body: formData
    })
    .then(async response => {
        console.log('Response status:', response.status);
        const text = await response.text(); // Get response as text first
        console.log('Raw response:', text);
        
        try {
            const data = JSON.parse(text); // Try to parse as JSON
            console.log('Parsed response:', data);
            return data;
        } catch (e) {
            console.error('JSON parse error:', e);
            throw new Error('Invalid server response: ' + text);
        }
    })
    .then(data => {
        console.log('Upload response:', data);
        if (data.success) {
            showAlert('File uploaded successfully', 'success');
            document.getElementById('fileUploadForm').reset();
            selectDoctor(currentFileDoctor, document.getElementById('selectedDoctor').textContent); // Reload files
        } else {
            const errorMessage = data.message || 'Failed to upload file';
            console.error('Upload failed:', errorMessage);
            showAlert(`Upload failed: ${errorMessage}`, 'danger');
        }
    })
    .catch(error => {
        console.error('Error uploading file:', error);
        showAlert(`Upload failed: ${error.message}. Please check the console for more details.`, 'danger');
    });
}

// Show alert message
function showAlert(message, type) {
    console.log('Showing alert:', message, type);
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        <div class="d-flex align-items-center">
            <div class="flex-grow-1">
                ${message}
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;
    
    // Find the files section
    const filesSection = document.getElementById('files');
    if (!filesSection) {
        console.error('Files section not found');
        return;
    }

    // Find the first child of the files section to insert before
    const firstChild = filesSection.firstChild;
    if (firstChild) {
        filesSection.insertBefore(alertDiv, firstChild);
    } else {
        filesSection.appendChild(alertDiv);
    }
    
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 10000);
}

// Initialize when document is ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('Document ready, setting up file section...');
    // Initialize files section when it becomes visible
    const filesSection = document.getElementById('files');
    if (filesSection) {
        console.log('Files section found');
        // Initialize immediately if files section is active
        if (filesSection.classList.contains('active')) {
            console.log('Files section is active, initializing...');
            initializeFiles();
        }

        // Set up observer for files section
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                    if (filesSection.classList.contains('active')) {
                        console.log('Files section became active, initializing...');
                        initializeFiles();
                    }
                }
            });
        });
        observer.observe(filesSection, { attributes: true });
        console.log('Files section observer set up');
    } else {
        console.error('Files section not found');
    }
}); 
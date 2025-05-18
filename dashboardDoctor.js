/*

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
                <a href="/FinalsWeb/functions/doctorFunction/download_shared_file.php?id=${file.id}" class="btn btn-sm btn-primary">
                    <i class="bi bi-download"></i> Download
                </a>
            </div>
        </div>
    </div>
`).join(''); 

// Initialize file upload form for doctor
function initializeDoctorFiles() {
    const fileUploadForm = document.getElementById('fileUploadForm');
    if (fileUploadForm) {
        // Remove any existing event listeners by cloning
        const newFileUploadForm = fileUploadForm.cloneNode(true);
        fileUploadForm.parentNode.replaceChild(newFileUploadForm, fileUploadForm);

        newFileUploadForm.addEventListener('submit', function(e) {
            e.preventDefault();
            e.stopPropagation();
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
            formData.append('csrf_token', document.getElementById('csrfToken').value);
            uploadFile(formData);
        });
    } else {
        console.error('File upload form not found');
    }
}

function uploadFile(formData) {
    console.log('Uploading file...', {
        patient_id: formData.get('patient_id'),
        file: formData.get('file'),
        csrf_token: formData.get('csrf_token')
    });

    fetch('/FinalsWeb/functions/doctorFunction/upload_file.php', {
        method: 'POST',
        body: formData
    })
    .then(async response => {
        console.log('Response status:', response.status);
        const text = await response.text();
        console.log('Raw response:', text);
        try {
            const data = JSON.parse(text);
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
            selectPatient(currentFilePatient, document.getElementById('selectedPatient').textContent); // Reload files
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
                    <a href="/FinalsWeb/functions/doctorFunction/download_shared_file.php?id=${file.id}" class="btn btn-sm btn-primary">
                        <i class="bi bi-download"></i> Download
                    </a>
                </div>
            </div>
        </div>
    `).join('');
    console.log('Files displayed');
} 

// General alert function
function showAlert(message, type = 'info') {
    // You can implement a more sophisticated alert/notification system here
    alert(message);
    console.log(`Alert (${type}): ${message}`);
}

// Function to handle selecting a patient (primarily for file section, but good to have)
let currentFilePatient = null; // Keep track of the selected patient for file operations

function selectPatient(patientId, patientName) {
    currentFilePatient = patientId;
    document.getElementById('selectedPatient').textContent = patientName;
    document.getElementById('fileUploadForm').style.display = 'block'; // Show file upload form

    // Fetch and display files for this patient
    fetch(`/FinalsWeb/functions/doctorFunction/get_files.php?patient_id=${patientId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                displayFiles(data.files);
            } else {
                showAlert('Error fetching files: ' + (data.message || 'Unknown error'), 'danger');
                document.getElementById('fileList').innerHTML = '<div class="text-center text-muted">Could not load files.</div>';
            }
        })
        .catch(error => {
            console.error('Error fetching files:', error);
            showAlert('Error fetching files: ' + error.message, 'danger');
            document.getElementById('fileList').innerHTML = '<div class="text-center text-muted">Error loading files. Please try again.</div>';
        });
}


function updateAppointmentStatus(appointmentId, status) {
    const csrfToken = document.getElementById('csrfToken').value;
    fetch('/FinalsWeb/functions/doctorFunction/update_appointment.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `appointment_id=${appointmentId}&status=${status}&csrf_token=${csrfToken}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('Appointment status updated successfully.', 'success');
            // Optional: Refresh the appointments list or update the UI directly
            location.reload(); // Simple reload for now
        } else {
            showAlert('Failed to update appointment status: ' + (data.message || 'Unknown error'), 'danger');
        }
    })
    .catch(error => {
        console.error('Error updating appointment status:', error);
        showAlert('Error updating appointment status. Please try again.', 'danger');
    });
}

function openRescheduleModal(appointmentId) {
    // Assuming you have a Bootstrap modal with id "rescheduleModal"
    // and an input field with id "rescheduleAppointmentId" inside the modal form
    const rescheduleModal = new bootstrap.Modal(document.getElementById('rescheduleModal'));
    document.getElementById('rescheduleAppointmentId').value = appointmentId;
    rescheduleModal.show();
}

function cancelAppointment(appointmentId) {
    if (confirm('Are you sure you want to cancel this appointment?')) {
        const csrfToken = document.getElementById('csrfToken').value;
        fetch('/FinalsWeb/functions/doctorFunction/cancel_appointment.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `appointment_id=${appointmentId}&csrf_token=${csrfToken}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('Appointment cancelled successfully.', 'success');
                // Optional: Refresh the appointments list or update the UI directly
                location.reload(); // Simple reload for now
            } else {
                showAlert('Failed to cancel appointment: ' + (data.message || 'Unknown error'), 'danger');
            }
        })
        .catch(error => {
            console.error('Error cancelling appointment:', error);
            showAlert('Error cancelling appointment. Please try again.', 'danger');
        });
    }
}

// Function to handle reschedule form submission
document.addEventListener('DOMContentLoaded', function() {
    const rescheduleForm = document.getElementById('rescheduleForm');
    if (rescheduleForm) {
        rescheduleForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const csrfToken = document.getElementById('csrfToken').value;
            formData.append('csrf_token', csrfToken);
            formData.append('requested_by', 'doctor'); // Add who is requesting

            fetch('/FinalsWeb/functions/doctorFunction/reschedule_appointment.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('Reschedule request submitted successfully.', 'success');
                    const rescheduleModal = bootstrap.Modal.getInstance(document.getElementById('rescheduleModal'));
                    if (rescheduleModal) {
                        rescheduleModal.hide();
                    }
                    location.reload(); // Reload to see changes
                } else {
                    showAlert('Failed to submit reschedule request: ' + (data.message || 'Unknown error'), 'danger');
                }
            })
            .catch(error => {
                console.error('Error submitting reschedule request:', error);
                showAlert('Error submitting reschedule request. Please try again.', 'danger');
            });
        });
    }
     initializeDoctorFiles(); // Initialize file uploads if the form is present
});

// Function to respond to a reschedule request
function respondReschedule(appointmentId, response) {
    const csrfToken = document.getElementById('csrfToken').value;
    fetch('/FinalsWeb/functions/doctorFunction/respond_reschedule.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `appointment_id=${appointmentId}&response=${response}&csrf_token=${csrfToken}`
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showAlert('Reschedule response submitted successfully.', 'success');
            location.reload();
        } else {
            showAlert('Failed to respond to reschedule: ' + (data.message || 'Unknown error'), 'danger');
        }
    })
    .catch(error => {
        console.error('Error responding to reschedule:', error);
        showAlert('Error responding to reschedule. Please try again.', 'danger');
    });
} 
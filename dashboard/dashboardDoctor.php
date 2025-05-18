<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'doctor') {
    header("Location: index.php");
    exit;
}

include '../dbConnect.php';

$doctorId = $_SESSION['user_id'];

// Fetch doctor profile from doctors table
$stmt = $conn->prepare("SELECT * FROM doctors WHERE account_id = ?");
if (!$stmt) {
    die("Query preparation failed: " . $conn->error);
}
$stmt->bind_param("i", $doctorId);
$stmt->execute();
$result = $stmt->get_result();
$doc = $result->fetch_assoc();
$docFound = is_array($doc); // safer check

// Debug output
error_log("Doctor ID: " . $doctorId);
error_log("Doctor Found: " . ($docFound ? 'Yes' : 'No'));
error_log("Doctor Data: " . print_r($doc, true));

// Fetch upcoming appointments
$appointments = [];
if ($docFound) {
    // Now fetch appointments with proper error handling
    try {
        $stmt = $conn->prepare("
            SELECT 
                a.*, 
                p.fullname as patient_name
            FROM appointments a 
            JOIN accounts p ON a.client_id = p.id 
            WHERE a.doctor_id = ? 
            AND a.date >= CURDATE() 
            ORDER BY a.date
        ");
        if (!$stmt) {
            throw new Exception("Query preparation failed: " . $conn->error);
        }
        $stmt->bind_param("i", $doctorId);
        if (!$stmt->execute()) {
            throw new Exception("Query execution failed: " . $stmt->error);
        }
        $result = $stmt->get_result();
        if (!$result) {
            throw new Exception("Failed to get result: " . $stmt->error);
        }
        $appointments = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    } catch (Exception $e) {
        echo '<div class="alert alert-danger">Error: ' . $e->getMessage() . '</div>';
    }
}

// Generate CSRF token only if not exists
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$token = $_SESSION['csrf_token'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Doctor Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="dashboardDoctor.css">
  <!-- Add hidden inputs for JavaScript variables -->

</head>
<body>
<div class="container-fluid">
  <div class="row">
    <!-- Sidebar -->
    <div class="col-md-2 sidebar d-flex flex-column p-0">
      <div class="p-3 border-bottom">
        <div class="d-flex align-items-center px-1 py-2">
          <img src="../img/imgSoloLogo.png" alt="Logo" class="me-2" style="height: 60px;">
          <img src="../img/imgSoloTitle.png" alt="Title" class="pt-3" style="height: 60px;">
        </div>
      </div>
      <nav class="nav flex-column">
        <a class="nav-link active" href="#" onclick="showSection('appointments')"><i class="bi bi-calendar me-3 fs-4"></i>Appointments</a>
        <a class="nav-link" href="#" onclick="showSection('calendar')"><i class="bi bi-calendar-check me-3 fs-4"></i>Calendar</a>
        <a class="nav-link" href="#" onclick="showSection('files')"><i class="bi bi-archive-fill me-3 fs-4"></i>Files</a>
        <a class="nav-link" href="#" onclick="showSection('chat')"><i class="bi bi-chat-left-text me-3 fs-4"></i>Chat</a>
        <a class="nav-link" href="#" onclick="showSection('viewProfile')"><i class="bi bi-person me-3 fs-4"></i>View Profile</a>
        <a class="nav-link" href="#" onclick="showSection('editProfile')"><i class="bi bi-pencil me-3 fs-4"></i>Edit Profile</a>
        <li class="nav-item">
            <a class="nav-link text-danger" href="../logout.php"><i class="bi bi-box-arrow-right me-3 fs-4"></i>Logout</a>
        </li>
      </nav>
    </div>

    <!-- Main Content -->
    <div class="col-md-10 p-4">
      <!-- Top Navbar -->
      <div id="topNavbar" class="d-flex justify-content-between align-items-center">
        <div id="navbarTitle" class="fw-bold fs-5">Welcome, <?= htmlspecialchars($doc['name'] ?? 'Doctor') ?></div>
        <div id="navbarRight">
          <a href="#" class="text-decoration-none text-dark" onclick="showSection('editProfile')">
            <i class="bi bi-gear-fill fs-4" title="Settings"></i>
          </a>
        </div>
      </div>

      <!-- Section: Appointments -->
      <div id="appointments" class="content-section">
        <h5>Appointments</h5>
        <ul class="nav nav-tabs mb-3" id="appointmentTabs" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active" id="upcoming-tab" data-bs-toggle="tab" data-bs-target="#upcoming" type="button" role="tab">Upcoming</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="history-tab" data-bs-toggle="tab" data-bs-target="#history" type="button" role="tab">History</button>
          </li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane fade show active" id="upcoming" role="tabpanel">
            <?php
            // Fetch upcoming appointments
            $upcoming = [];
            if ($docFound) {
              $stmt = $conn->prepare("
                SELECT a.*, p.fullname as patient_name
                FROM appointments a
                JOIN accounts p ON a.client_id = p.id
                WHERE a.doctor_id = ? AND a.date >= CURDATE() AND a.status NOT IN ('cancelled','completed')
                ORDER BY a.date
              ");
              $stmt->bind_param("i", $doctorId);
              $stmt->execute();
              $result = $stmt->get_result();
              $upcoming = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
              $stmt->close();
            }
            ?>
            <div class="table-responsive">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>Date</th>
                    <th>Patient</th>
                    <th>Reason</th>
                    <th>Status</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($upcoming as $appointment): ?>
                  <tr>
                    <td><?= htmlspecialchars(date('M d, Y', strtotime($appointment['date']))) ?></td>
                    <td><?= htmlspecialchars($appointment['patient_name']) ?></td>
                    <td><?= htmlspecialchars($appointment['reason'] ?? 'N/A') ?></td>
                    <td>
                      <span class="badge bg-secondary">
                        <?= ucfirst($appointment['status']) ?>
                      </span>
                      <?php if (!empty($appointment['reschedule_status']) && $appointment['reschedule_status'] === 'pending'): ?>
                        <span class="badge bg-warning text-dark ms-1">Reschedule Pending</span>
                      <?php endif; ?>
                    </td>
                    <td>
                      <div class="btn-group">
                        <?php if ($appointment['status'] === 'pending'): ?>
                          <button class="btn btn-sm btn-success" onclick="updateAppointmentStatus(<?= $appointment['id'] ?>, 'confirmed')">Accept</button>
                        <?php endif; ?>
                      
                        <button class="btn btn-sm btn-danger" onclick="cancelAppointment(<?= $appointment['id'] ?>)">Cancel</button>
                        <?php if (!empty($appointment['reschedule_status']) && $appointment['reschedule_status'] === 'pending' && $appointment['reschedule_requested_by'] === 'client'): ?>
                          <button class="btn btn-sm btn-success" onclick="respondReschedule(<?= $appointment['id'] ?>, 'accept')">Accept</button>
                          <button class="btn btn-sm btn-secondary" onclick="respondReschedule(<?= $appointment['id'] ?>, 'decline')">Decline</button>
                        <?php endif; ?>
                      </div>
                    </td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
          <div class="tab-pane fade" id="history" role="tabpanel">
            <?php
            // Fetch history appointments
            $history = [];
            if ($docFound) {
              $stmt = $conn->prepare("
                SELECT a.*, p.fullname as patient_name
                FROM appointments a
                JOIN accounts p ON a.client_id = p.id
                WHERE a.doctor_id = ? AND (a.date < CURDATE() OR a.status IN ('cancelled','completed'))
                ORDER BY a.date DESC
              ");
              $stmt->bind_param("i", $doctorId);
              $stmt->execute();
              $result = $stmt->get_result();
              $history = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
              $stmt->close();
            }
            ?>
            <div class="table-responsive">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>Date</th>
                    <th>Patient</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($history as $appointment): ?>
                  <tr>
                    <td><?= htmlspecialchars(date('M d, Y', strtotime($appointment['date']))) ?></td>
                    <td><?= htmlspecialchars($appointment['patient_name']) ?></td>
                    <td>
                      <span class="badge bg-secondary">
                        <?= ucfirst($appointment['status']) ?>
                      </span>
                      <?php if (!empty($appointment['reschedule_status']) && $appointment['reschedule_status'] === 'pending'): ?>
                        <span class="badge bg-warning text-dark ms-1">Reschedule Pending</span>
                      <?php endif; ?>
                    </td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      

      <!-- Section: Calendar -->
      <div id="calendar" class="content-section">
        <h5>Set Unavailability</h5>
        <form id="availabilityForm" action="../functions/doctorFunction/update_availability.php" method="POST" class="needs-validation" novalidate>
          <input type="hidden" name="csrf_token" value="<?= $token ?>">
          <div class="row">
            <div class="col-md-3">
              <div class="mb-3">
                <label class="form-label">From</label>
                <input type="date" name="from_date" class="form-control" id="calendarFromDate" required min="<?= date('Y-m-d') ?>">
              </div>
            </div>
            <div class="col-md-3">
              <div class="mb-3">
                <label class="form-label">To</label>
                <input type="date" name="to_date" class="form-control" id="calendarToDate" required min="<?= date('Y-m-d') ?>">
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label">Time Slots</label>
                <div class="time-slots">
                  <?php
                  $start = strtotime('09:00');
                  $end = strtotime('17:00');
                  for ($time = $start; $time <= $end; $time += 3600) {
                    echo '<div class="form-check d-inline-block me-3 mb-2">';
                    echo '<input type="checkbox" name="time_slots[]" value="' . date('H:i', $time) . '" class="form-check-input">';
                    echo '<label class="form-check-label">' . date('h:i A', $time) . '</label>';
                    echo '</div>';
                  }
                  ?>
                </div>
                <small class="text-muted">All selected slots in the date range will be marked unavailable.</small>
              </div>
            </div>
          </div>
          <div id="unavailableSlotsSection" class="mb-3">
            <label class="form-label">Unavailable Time Slots for Selected Date:</label>
            <div id="unavailableSlotsList" class="d-flex flex-wrap gap-2"></div>
          </div>
          <button type="submit" class="btn btn-primary">
            <span class="loading spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            Save Unavailability
          </button>
          <div id="calendarMsg" class="mt-2"></div>
        </form>
      </div>

      <!-- Files Section -->
      <div id="files" class="content-section">
        <div class="row">
          <!-- Patient List Sidebar -->
          <div class="col-md-4 border-end" style="height: 500px; overflow-y: auto; background: #f8f9fa;">
            <div class="p-2 border-bottom bg-white sticky-top">
              <strong>Patients</strong>
            </div>
            <div class="list-group" id="filePatientList" style="height: 440px; overflow-y: auto;">
              <?php
              // Fetch only patients who have exchanged files with the doctor
              $filePatients = [];
              $sql = "
                  SELECT a.id, a.fullname as name, MAX(f.upload_date) as last_upload
                  FROM shared_files f
                  JOIN accounts a ON (
                      (f.sender_id = a.id AND f.recipient_id = ?)
                      OR
                      (f.recipient_id = a.id AND f.sender_id = ?)
                  )
                  WHERE ? IN (f.sender_id, f.recipient_id)
                  GROUP BY a.id, a.fullname
                  ORDER BY last_upload DESC
              ";
              $stmt = $conn->prepare($sql);
              if ($stmt) {
                  $stmt->bind_param("iii", $doctorId, $doctorId, $doctorId);
                  $stmt->execute();
                  $result = $stmt->get_result();
                  $filePatients = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
                  $stmt->close();
              } else {
                  echo '<script>console.error("SQL prepare failed for filePatients: ' . addslashes($conn->error) . '\nQuery: ' . addslashes($sql) . '");</script>';
              }
              ?>
              <?php foreach ($filePatients as $patient): ?>
                <a href="#" class="list-group-item list-group-item-action" onclick="selectPatient(<?= $patient['id'] ?>, '<?= htmlspecialchars($patient['name'], ENT_QUOTES) ?>')">
                  <div>
                    <h6 class="mb-0"><?= htmlspecialchars($patient['name']) ?></h6>
                  </div>
                </a>
              <?php endforeach; ?>
            </div>
          </div>
          <!-- Files Area -->
          <div class="col-md-8 d-flex flex-column" style="height: 500px;">
            <div class="p-2 border-bottom bg-white sticky-top">
              <h6 class="mb-0">Selected Patient: <span id="selectedPatient" class="text-muted">None</span></h6>
            </div>
            <div class="p-3 border rounded bg-white flex-grow-1" style="overflow-y: auto;">
              <form id="fileUploadForm" class="mb-4" style="display: none;">
                <div class="mb-3">
                  <label for="fileInput" class="form-label">Upload File</label>
                  <input type="file" class="form-control" id="fileInput" name="file" required>
                  <div class="form-text">Maximum file size: 5MB</div>
                </div>
                <button type="submit" class="btn btn-primary">Upload</button>
              </form>
              <div class="list-group" id="fileList">
                <!-- Shared files will be loaded here -->
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Section: Chat -->
      <div id="chat" class="content-section">
        <div class="row">
          <!-- Chat List Sidebar -->
          <div class="col-md-4 border-end" style="height: 500px; overflow-y: auto; background: #f8f9fa;">
            <div class="p-2 border-bottom bg-white sticky-top">
              <strong>Clients</strong>
            </div>
            <div class="list-group" id="chatClientList" style="height: 440px; overflow-y: auto;">
              <?php
              // Fetch only users who have chatted with the doctor, ordered by last message
              $chatClients = [];
              $stmt = $conn->prepare("
                SELECT a.id as account_id, a.fullname as name, MAX(m.created_at) as last_message
                FROM chat_messages m
                JOIN accounts a ON m.sender_id = a.id
                WHERE m.receiver_id = ?
                GROUP BY a.id, a.fullname
                ORDER BY last_message DESC
              ");
              if ($stmt) {
                  $stmt->bind_param("i", $doctorId);
                  $stmt->execute();
                  $result = $stmt->get_result();
                  $chatClients = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
                  $stmt->close();
              } else {
                  echo '<div class="alert alert-danger">Error preparing chat client query: ' . $conn->error . '</div>';
              }
              ?>
              <?php foreach ($chatClients as $client): ?>
                <a href="#" class="list-group-item list-group-item-action" onclick="loadChat(<?= $client['account_id'] ?>)">
                  <div>
                    <h6 class="mb-0"><?= htmlspecialchars($client['name']) ?></h6>
                  </div>
                </a>
              <?php endforeach; ?>
            </div>
          </div>
          <!-- Chat Messages Area -->
          <div class="col-md-8 d-flex flex-column" style="height: 500px;">
            <div class="p-2 border-bottom bg-white sticky-top" id="chatHeader">
              <span id="currentChatClient" class="fw-bold">Select a client to start chatting</span>
            </div>
            <div class="chat-messages flex-grow-1 p-3 border rounded bg-white" style="overflow-y: auto; min-height: 350px;">
              <div class="text-center text-muted">Select a client to start chatting</div>
            </div>
            <form id="chatForm" class="mt-3" style="display: none;">
              <div class="input-group">
                <input type="text" class="form-control" placeholder="Type your message...">
                <button class="btn btn-primary" type="submit">Send</button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- Section: View Profile -->
      <div id="viewProfile" class="content-section">
        <div class="card">
          <div class="card-header">
            <h5 class="mb-0">Doctor Profile</h5>
          </div>
          <div class="card-body">
            <?php if (isset($docFound) && $docFound): ?>
              <div class="row">
                <div class="col-md-4 text-center mb-4">
                  <img src="<?= isset($doc['image']) && $doc['image'] ? '/FinalsWeb/' . htmlspecialchars($doc['image']) : '/FinalsWeb/img/default-doctor.png' ?>"
                       class="img-fluid rounded-circle mb-3"
                       alt="<?= htmlspecialchars($doc['name']) ?>"
                       style="max-width: 200px; height: 200px; object-fit: cover;">
                  <h4><?= htmlspecialchars($doc['name']) ?></h4>
                  <p class="text-muted"><?= htmlspecialchars($doc['specialty'] ?? 'N/A') ?></p>
                </div>
                <div class="col-md-8">
                  <div class="row">
                    <div class="col-md-6 mb-3">
                      <label class="text-muted">Subspecialty</label>
                      <p class="mb-0"><?= htmlspecialchars($doc['subspecialty'] ?? 'N/A') ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                      <label class="text-muted">Experience</label>
                      <p class="mb-0"><?= htmlspecialchars($doc['experience'] ?? 'N/A') ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                      <label class="text-muted">Availability</label>
                      <p class="mb-0">
                        <?php
                        $availability = [];
                        if (!empty($doc['in_person'])) $availability[] = 'In-Person';
                        if (!empty($doc['online'])) $availability[] = 'Online';
                        echo !empty($availability) ? implode(' / ', $availability) : 'N/A';
                        ?>
                      </p>
                    </div>
                    <div class="col-md-6 mb-3">
                      <label class="text-muted">Fee</label>
                      <p class="mb-0"><?= htmlspecialchars($doc['fee'] ?? 'N/A') ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                      <label class="text-muted">Affiliation</label>
                      <p class="mb-0"><?= htmlspecialchars($doc['affiliation'] ?? 'N/A') ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                      <label class="text-muted">Education</label>
                      <p class="mb-0"><?= htmlspecialchars($doc['education'] ?? 'N/A') ?></p>
                    </div>
                    <div class="col-12 mb-3">
                      <label class="text-muted">Certifications</label>
                      <p class="mb-0"><?= htmlspecialchars($doc['certifications'] ?? 'N/A') ?></p>
                    </div>
                    <div class="col-12">
                      <label class="text-muted">Biography</label>
                      <p class="mb-0"><?= htmlspecialchars($doc['bio'] ?? 'N/A') ?></p>
                    </div>
                  </div>
                </div>
              </div>
            <?php else: ?>
              <div class="alert alert-warning">
                <i class="bi bi-exclamation-triangle"></i>
                Please complete your profile information in the Edit Profile section.
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <!-- Section: Edit Profile -->
      <div id="editProfile" class="content-section">
        <div class="card">
          <div class="card-header">
            <h5 class="mb-0">Edit Profile</h5>
          </div>
          <div class="card-body">
            <?php if (isset($docFound) && $docFound): ?>
            <form id="editProfileForm" action="../functions/doctorFunction/update_profile.php" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
              <input type="hidden" name="csrf_token" value="<?= $token ?>">
              <div class="row">
                <!-- Name Field -->
                <div class="col-md-6 mb-3">
                  <label class="form-label">Name</label>
                  <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($doc['name'] ?? '') ?>">
                </div>

                <!-- Specialty Field -->
                <div class="col-md-6 mb-3">
                  <label class="form-label">Specialty</label>
                  <input type="text" name="specialty" class="form-control" value="<?= htmlspecialchars($doc['specialty'] ?? '') ?>">
                </div>

                <!-- Subspecialty Field -->
                <div class="col-md-6 mb-3">
                  <label class="form-label">Subspecialty</label>
                  <input type="text" name="subspecialty" class="form-control" value="<?= htmlspecialchars($doc['subspecialty'] ?? '') ?>">
                </div>

                <!-- Experience Field -->
                <div class="col-md-6 mb-3">
                  <label class="form-label">Experience</label>
                  <input type="text" name="experience" class="form-control" value="<?= htmlspecialchars($doc['experience'] ?? '') ?>">
                </div>

                <!-- Fee Field -->
                <div class="col-md-6 mb-3">
                  <label class="form-label">Fee</label>
                  <input type="text" name="fee" class="form-control" value="<?= htmlspecialchars($doc['fee'] ?? '') ?>">
                </div>

                <!-- Affiliation Field -->
                <div class="col-md-6 mb-3">
                  <label class="form-label">Affiliation</label>
                  <input type="text" name="affiliation" class="form-control" value="<?= htmlspecialchars($doc['affiliation'] ?? '') ?>">
                </div>

                <!-- Education Field -->
                <div class="col-md-6 mb-3">
                  <label class="form-label">Education</label>
                  <input type="text" name="education" class="form-control" value="<?= htmlspecialchars($doc['education'] ?? '') ?>">
                </div>

                <!-- Certifications Field -->
                <div class="col-md-6 mb-3">
                  <label class="form-label">Certifications</label>
                  <input type="text" name="certifications" class="form-control" value="<?= htmlspecialchars($doc['certifications'] ?? '') ?>">
                </div>

                <!-- Biography Field -->
                <div class="col-12 mb-3">
                  <label class="form-label">Biography</label>
                  <textarea name="bio" class="form-control" rows="4"><?= htmlspecialchars($doc['bio'] ?? '') ?></textarea>
                </div>

                <!-- Availability Field -->
                <div class="col-12 mb-3">
                  <label class="form-label">Availability</label>
                  <div class="form-check">
                    <input type="checkbox" id="in_person" name="in_person" value="1" class="form-check-input" <?= (!empty($doc['in_person'])) ? 'checked' : '' ?>>
                    <label for="in_person" class="form-check-label">In-Person</label>
                  </div>
                  <div class="form-check">
                    <input type="checkbox" id="online" name="online" value="1" class="form-check-input" <?= (!empty($doc['online'])) ? 'checked' : '' ?>>
                    <label for="online" class="form-check-label">Online</label>
                  </div>
                </div>

                <!-- Profile Image Field -->
                <div class="col-12 mb-3">
                  <label class="form-label">Profile Image</label>
                  <input type="file" name="image" class="form-control" accept="image/*">
                  <small class="form-text text-muted">Leave empty to keep current image</small>
                </div>

                <div class="col-12">
                  <button type="submit" class="btn btn-primary">
                    <span class="loading spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    Save Changes
                  </button>
                </div>
              </div>
              <div class="error-message mt-3"></div>
              <div class="success-message mt-3"></div>
            </form>
            <?php else: ?>
              <div class="alert alert-warning">
                <i class="bi bi-exclamation-triangle"></i>
                Profile editing is unavailable. Please contact support.
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <!-- Messages Section -->
      <div id="messages" class="content-section">
        <div class="row">
          <div class="col-md-4">
            <!-- Search Box -->
            <div class="mb-3">
              <input type="text" class="form-control" id="searchClient" placeholder="Search clients...">
            </div>
            <!-- Client List -->
            <div class="list-group" id="chatClientList">
              <!-- Client list will be loaded here -->
            </div>
          </div>
          <div class="col-md-8">
            <!-- Chat Messages -->
            <div class="chat-messages p-3 border rounded" style="height: 400px; overflow-y: auto;">
              <div class="text-center text-muted">Select a client to start chatting</div>
            </div>
            <!-- Message Input -->
            <form id="chatForm" class="mt-3" style="display: none;">
              <div class="input-group">
                <input type="text" class="form-control" placeholder="Type your message...">
                <button class="btn btn-primary" type="submit">Send</button>
              </div>
            </form>
          </div>
        </div>
      </div>

<!-- Add hidden inputs for JavaScript variables -->
<input type="hidden" id="doctorId" value="<?= $doctorId ?>">
<input type="hidden" id="csrfToken" value="<?= $token ?>">
<script src="dashboardDoctor.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  const form = document.getElementById('availabilityForm');
  const fromDateInput = document.getElementById('calendarFromDate');
  const toDateInput = document.getElementById('calendarToDate');
  const unavailableSlotsList = document.getElementById('unavailableSlotsList');
  const timeSlotCheckboxes = document.querySelectorAll('input[name="time_slots[]"]');

  function fetchUnavailableSlots(fromDate, toDate) {
    fetch('../api/get_doctor_unavailability.php?from_date=' + encodeURIComponent(fromDate) + '&to_date=' + encodeURIComponent(toDate))
      .then(res => res.json())
      .then(slots => {
        // Uncheck all first
        timeSlotCheckboxes.forEach(cb => cb.checked = false);
        unavailableSlotsList.innerHTML = '';
        slots.forEach(slot => {
          // Check the corresponding checkbox
          timeSlotCheckboxes.forEach(cb => {
            if (cb.value === slot) cb.checked = true;
          });
          // Show in the list with a remove button
          const btn = document.createElement('button');
          btn.type = 'button';
          btn.className = 'btn btn-sm btn-danger me-2 mb-2';
          btn.textContent = slot;
          btn.onclick = function() {
            // Remove this slot from unavailability
            fetch('../functions/doctorFunction/update_availability.php', {
              method: 'POST',
              body: new URLSearchParams({
                from_date: fromDate,
                to_date: toDate,
                time_slots: Array.from(timeSlotCheckboxes).filter(cb => cb.checked && cb.value !== slot).map(cb => cb.value)
              })
            }).then(() => {
              // Uncheck the box and refresh the list
              timeSlotCheckboxes.forEach(cb => {
                if (cb.value === slot) cb.checked = false;
              });
              fetchUnavailableSlots(fromDate, toDate);
            });
          };
          unavailableSlotsList.appendChild(btn);
        });
      });
  }

  if (fromDateInput && toDateInput) {
    fromDateInput.addEventListener('change', function() {
      if (this.value && toDateInput.value) fetchUnavailableSlots(this.value, toDateInput.value);
    });
    toDateInput.addEventListener('change', function() {
      if (this.value && fromDateInput.value) fetchUnavailableSlots(fromDateInput.value, this.value);
    });
    // Initial load
    if (fromDateInput.value && toDateInput.value) fetchUnavailableSlots(fromDateInput.value, toDateInput.value);
  }

  if (form) {
    form.addEventListener('submit', function(e) {
      e.preventDefault();
      const formData = new FormData(form);
      fetch(form.action, {
        method: 'POST',
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        const msg = document.getElementById('calendarMsg');
        if (data.success) {
          msg.innerHTML = '<div class="alert alert-success">Availability updated!</div>';
          if (fromDateInput.value && toDateInput.value) fetchUnavailableSlots(fromDateInput.value, toDateInput.value);
        } else {
          msg.innerHTML = '<div class="alert alert-danger">' + (data.message || 'Failed to update availability.') + '</div>';
        }
      })
      .catch(() => {
        document.getElementById('calendarMsg').innerHTML = '<div class="alert alert-danger">Error updating availability.</div>';
      });
    });
  }
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

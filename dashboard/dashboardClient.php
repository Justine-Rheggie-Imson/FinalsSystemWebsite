<?php
session_start();
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'client') {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>HealthServe Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="dashboardDoctor.css">
  
</head>
<body>


<div class="container-fluid">
  <div class="row">
    <!-- Sidebar (hidden on mobile, visible on md+) -->
    <nav class="col-md-2 d-none d-md-block bg-light sidebar p-0">
      <div class="p-3 border-bottom">
        <div class="d-flex align-items-center px-1 py-2">
          <img src="../img/imgSoloLogo.png" alt="Logo" class="me-2" style="height: 60px;">
          <img src="../img/imgSoloTitle.png" alt="Title" class="pt-3" style="height: 60px;">
        </div>
      </div>
      <nav class="nav flex-column">
        <a class="nav-link" href="../index.php"><i class="bi bi-house-door me-3 fs-4"></i>Home</a>
        <a class="nav-link" href="../booking/booking.php"  id="nav-booking"><i class="bi bi-bookmark me-3 fs-4"></i>Book Now</a>
        <a class="nav-link" href="#" onclick="showSection('appointments')" id="nav-appointments"><i class="bi bi-calendar me-3 fs-4"></i>Appointments</a>
        <a class="nav-link" href="#" onclick="showSection('files')" id="nav-files"><i class="bi bi-archive-fill me-3 fs-4"></i>Files</a>
        <a class="nav-link" href="#" onclick="showSection('messages')" id="nav-messages"><i class="bi bi-chat-left-text me-3 fs-4"></i>Messages</a>
        <a class="nav-link text-danger mt-3" href="../logout.php"><i class="bi bi-box-arrow-right me-3 fs-4"></i>Logout</a>
      </nav>
    </nav>
    <!-- Main Content -->
    <main class="col-12 col-md-10 p-4">
      <!-- Top Navbar -->
<div id="topNavbar" class="d-flex justify-content-between align-items-center mb-4 px-2 py-2 bg-white shadow-sm">
  <div id="navbarTitle" class="fw-bold fs-5">Welcome, Justine</div>
  <div id="navbarRight">
    <a href="editClientInfo.php" class="text-decoration-none text-dark">
      <i class="bi bi-gear-fill fs-4" title="Settings"></i>
    </a>
  </div>
</div>


      <!-- Home Section -->
      <div id="home" class="content-section">
        <div class="row mt-4">
          <div class="col-md-6">
            <div class="p-4 bg-success text-white rounded shadow-sm">
              <h5>Got an Invite Code?</h5>
              <p>Enter your code manually</p>
            </div>
          </div>
          <div class="col-md-6">
            <div class="p-4 bg-primary text-white rounded shadow-sm">
              <h5>Need a Doctor?</h5>
              <p>Find the right one for you</p>
            </div>
          </div>
        </div>
        <div class="mt-5">
          <h6>Explore</h6>
          
        </div>
      </div>

      <!-- Appointments Section -->
      <div id="appointments" class="content-section active">
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
            if (isset($_SESSION['user_id']) && isset($_SESSION['role']) && $_SESSION['role'] === 'client') {
                $clientId = $_SESSION['user_id'];
                
                include '../dbConnect.php'; // Ensure this path is correct

                if (isset($conn) && !$conn->connect_error) {
                    $sql = "
                      SELECT a.*, d.name as doctor_name
                      FROM appointments a
                      JOIN doctors d ON a.doctor_id = d.account_id
                      WHERE a.client_id = ? AND a.date >= CURDATE() AND a.status NOT IN ('cancelled','completed')
                      ORDER BY a.date
                    ";
                    $stmt = $conn->prepare($sql);
                    
                    if ($stmt) {
                        $stmt->bind_param("i", $clientId);
                        if ($stmt->execute()) {
                            $result = $stmt->get_result();
                            $upcoming = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
                        } else {
                            error_log("Client Dashboard - Upcoming Appointments - Execute Error: " . $stmt->error);
                            $upcoming = []; // Ensure upcoming is empty on error
                        }
                        $stmt->close();
                    } else {
                        error_log("Client Dashboard - Upcoming Appointments - Prepare Error: " . $conn->error);
                        $upcoming = []; // Ensure upcoming is empty on error
                    }
                    // $conn->close(); // Usually handled by dbConnect or at script end
                } else {
                    error_log("Client Dashboard - Upcoming Appointments - DB Connection Error: " . ($conn->connect_error ?? 'Connection object not found'));
                    $upcoming = []; // Ensure upcoming is empty on error
                }
            } else {
                error_log("Client Dashboard - Upcoming Appointments - Session Error: User not logged in or not a client.");
                $upcoming = []; // Ensure upcoming is empty on error
            }
            ?>
            <div class="table-responsive">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>Date</th>
                    <th>Doctor</th>
                    <th>Status</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($upcoming as $appointment): ?>
                  <tr>
                    <td><?= htmlspecialchars(date('M d, Y', strtotime($appointment['date']))) ?></td>
                    <td><?= htmlspecialchars($appointment['doctor_name']) ?></td>
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
                        <button class="btn btn-sm btn-danger" onclick="cancelAppointment(<?= $appointment['id'] ?>)">Cancel</button>
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
            if (isset($_SESSION['user_id']) && isset($_SESSION['role']) && $_SESSION['role'] === 'client') {
                $clientId = $_SESSION['user_id'];

                include '../dbConnect.php'; // Ensure this path is correct

                if (isset($conn) && !$conn->connect_error) {
                    $stmt = $conn->prepare("
                      SELECT a.*, d.name as doctor_name
                      FROM appointments a
                      JOIN doctors d ON a.doctor_id = d.account_id
                      WHERE a.client_id = ? AND (a.date < CURDATE() OR a.status IN ('cancelled','completed'))
                      ORDER BY a.date DESC
                    ");
                    if ($stmt) {
                        $stmt->bind_param("i", $clientId);
                        if ($stmt->execute()) {
                            $result = $stmt->get_result();
                            $history = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
                        } else {
                            error_log("Client Dashboard - History Appointments - Execute Error: " . $stmt->error);
                            $history = []; // Ensure history is empty on error
                        }
                        $stmt->close();
                    } else {
                        error_log("Client Dashboard - History Appointments - Prepare Error: " . $conn->error);
                        $history = []; // Ensure history is empty on error
                    }
                    // $conn->close(); // Usually handled by dbConnect or at script end
                } else {
                    error_log("Client Dashboard - History Appointments - DB Connection Error: " . ($conn->connect_error ?? 'Connection object not found'));
                    $history = []; // Ensure history is empty on error
                }
            } else {
                error_log("Client Dashboard - History Appointments - Session Error: User not logged in or not a client.");
                $history = []; // Ensure history is empty on error
            }
            ?>
            <div class="table-responsive">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>Date</th>
                    <th>Doctor</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($history as $appointment): ?>
                  <tr>
                    <td><?= htmlspecialchars(date('M d, Y', strtotime($appointment['date']))) ?></td>
                    <td><?= htmlspecialchars($appointment['doctor_name']) ?></td>
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

      <!-- Files Section -->
      <div id="files" class="content-section">
        <div class="row">
          <!-- Doctor List Sidebar -->
          <div class="col-md-4 border-end" style="height: 500px; overflow-y: auto; background: #f8f9fa;">
            <div class="p-2 border-bottom bg-white sticky-top">
              <strong>Doctors</strong>
            </div>
            <div class="list-group" id="doctorList" style="height: 440px; overflow-y: auto;">
              <!-- Doctor list will be loaded here -->
            </div>
          </div>
          <!-- Files Area -->
          <div class="col-md-8 d-flex flex-column" style="height: 500px;">
            <div class="p-2 border-bottom bg-white sticky-top">
              <h6 class="mb-0">Selected Doctor: <span id="selectedDoctor" class="text-muted">None</span></h6>
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

      <!-- Messages Section -->
      <div id="messages" class="content-section">
        <div class="row">
          <!-- Chat List Sidebar -->
          <div class="col-md-4 border-end" style="height: 500px; overflow-y: auto; background: #f8f9fa;">
            <div class="p-2 border-bottom bg-white sticky-top">
              <strong>Doctors</strong>
            </div>
            <div class="list-group" id="chatDoctorList" style="height: 440px; overflow-y: auto;">
              <!-- Doctor list will be loaded here -->
            </div>
          </div>
          <!-- Chat Messages Area -->
          <div class="col-md-8 d-flex flex-column" style="height: 500px;">
            <div class="p-2 border-bottom bg-white sticky-top" id="chatHeader">
              <span id="currentChatDoctor" class="fw-bold">Select a doctor to start chatting</span>
            </div>
            <div class="chat-messages flex-grow-1 p-3 border rounded bg-white" style="overflow-y: auto; min-height: 350px;">
              <div class="text-center text-muted">Select a doctor to start chatting</div>
            </div>
            <form id="chatForm" class="mt-3" style="display: none;" onsubmit="return false;">
              <div class="input-group">
                <input type="text" class="form-control" placeholder="Type your message...">
                <button class="btn btn-primary" type="submit" id="sendMessageBtn">Send</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </main>
  </div>
</div>

<!-- Reschedule Modal -->
<div class="modal" id="rescheduleModal" tabindex="-1">
  <div class="modal-dialog">
    <form id="rescheduleForm">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Reschedule Appointment</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="appointment_id" id="rescheduleAppointmentId">
          <div class="mb-3">
            <label>Date</label>
            <input type="date" class="form-control" name="new_date" required>
          </div>
          <div class="mb-3">
            <label>Time</label>
            <input type="time" class="form-control" name="new_time" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Request Reschedule</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Add CSRF token -->
<input type="hidden" id="csrfToken" value="<?= $_SESSION['csrf_token'] ?>">

<!-- Load chat.js first -->
<script src="../js/chat.js"></script>
<script src="../js/files.js"></script>
<script src="../dashboardClient.js"></script>

<script>
// Highlight the active sidebar button based on the visible section
function updateSidebarActive(sectionId) {
  document.querySelectorAll('.sidebar .nav-link').forEach(link => {
    link.classList.remove('active');
  });
  if (sectionId === 'appointments') {
    document.getElementById('nav-appointments').classList.add('active');
  } else if (sectionId === 'files') {
    document.getElementById('nav-files').classList.add('active');
  } else if (sectionId === 'messages') {
    document.getElementById('nav-messages').classList.add('active');
  }
}

// Patch showSection to update sidebar highlight
const origShowSection = window.showSection;
window.showSection = function(sectionId) {
  origShowSection(sectionId);
  updateSidebarActive(sectionId);
};

// On page load, highlight the correct sidebar button
document.addEventListener('DOMContentLoaded', function() {
  updateSidebarActive('appointments');
});
</script>

</body>
</html>

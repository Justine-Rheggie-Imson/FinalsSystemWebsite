<?php
session_start();
require_once __DIR__ . '/../config/database.php'; // Correct path for config

// Redirect if not logged in as client
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'client') {
    $_SESSION['show_login'] = true;
    $_SESSION['login_message'] = "Please login first to book an appointment.";
    header("Location: ../index.php");
    exit;
}

$success = $error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $client_id = $_SESSION['user_id'];
    $doctor_id = $_POST['doctor_id'] ?? '';
    $date = $_POST['date'] ?? '';
    $time = $_POST['time'] ?? '';
    $reason = $_POST['reason'] ?? '';
    $status = 'pending';
    $filePath = null;

    // Handle file upload
    if (isset($_FILES['document']) && $_FILES['document']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../uploads/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
        $filename = uniqid() . '_' . basename($_FILES['document']['name']);
        $filePath = 'uploads/' . $filename; // relative path for DB
        move_uploaded_file($_FILES['document']['tmp_name'], $uploadDir . $filename);
    }

    if ($doctor_id && $date && $time && $reason) {
        $stmt = $conn->prepare("INSERT INTO appointments (client_id, doctor_id, date, time, reason, file_path, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iisssss", $client_id, $doctor_id, $date, $time, $reason, $filePath, $status);
        if ($stmt->execute()) {
            $success = "Appointment booked successfully!";
        } else {
            $error = "Error booking appointment: " . $conn->error;
        }
    } else {
        $error = "Please fill in all required fields.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Book Appointment | HealthServe</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .booking-banner {
      background: url('../img/booking-banner.jpg') no-repeat center center/cover;
      min-height: 250px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
    }
    .form-section {
      background-color: #f8f9fa;
      padding: 40px;
      border-radius: 8px;
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
  <div class="container">
  <a href="../index.php">
  <div class="d-flex align-items-center px-1 py-2">
        <img src="../img/imgSoloLogo.png" alt="Logo" class="me-2" style="height: 60px;">
        <img src="../img/imgSoloTitle.png" alt="Title" class="pt-3" style="height: 60px;">
      </div>
  </a>
  </div>
</nav>

<!-- Hero/Banner -->
<section class="booking-banner">
  <div class="text-center">
    <h1 class="display-5 fw-bold">Book an Appointment</h1>
    <p class="lead">Easy and fast. Choose your doctor, time, and you're all set!</p>
  </div>
</section>

<!-- Booking Form -->
<section class="container my-5">
  <div class="form-section">
    <?php if (isset($_SESSION['login_message'])): ?>
      <div class="alert alert-warning"><?= $_SESSION['login_message']; unset($_SESSION['login_message']); ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
      <div class="alert alert-success"><?= $success ?></div>
    <?php elseif ($error): ?>
      <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>
    <form action="" method="POST" enctype="multipart/form-data">
      <div class="row mb-3">
        
        <div class="col-md-3">
          <label class="form-label">Date</label>
          <input type="date" class="form-control" name="date" id="bookingDate" required>
        </div>
        <div class="col-md-3">
          <label class="form-label">Time</label>
          <input type="time" class="form-control" name="time" id="bookingTime" required>
        </div>
      </div>
      <div class="col-md-6 mb-3" >
          <label class="form-label">Select Doctor</label>
          <select class="form-select" name="doctor_id" id="doctorSelect" required>
            <option >Choose a doctor</option>
            <?php
            $result = $conn->query("SELECT d.account_id, d.name, d.specialty FROM doctors d JOIN accounts a ON d.account_id = a.id");
            while ($row = $result->fetch_assoc()) {
                echo '<option value="' . $row['account_id'] . '">' . htmlspecialchars($row['name']) . ' - ' . htmlspecialchars($row['specialty']) . '</option>';
            }
            ?>
          </select>
        </div>
      <div class="mb-3">
        <label class="form-label">Reason for Visit</label>
        <textarea class="form-control" rows="3" name="reason" placeholder="Brief description" required></textarea>
      </div>
    
      <div class="text-center mt-4">
        <button type="submit" class="btn btn-success px-5">Confirm Booking</button>
      </div>
    </form>
  </div>
</section>

<!-- Footer -->
<footer class="bg-dark text-light text-center py-4 mt-5">
  <p class="mb-0">&copy; 2025 HealthServe. All rights reserved.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  function updateDoctorDropdown() {
    var date = document.getElementById('bookingDate').value;
    var time = document.getElementById('bookingTime').value;
    var doctorSelect = document.getElementById('doctorSelect');
    if (date && time) {
      fetch('../api/get_available_doctors.php?date=' + encodeURIComponent(date) + '&time=' + encodeURIComponent(time))
        .then(res => res.json())
        .then(doctors => {
          doctorSelect.innerHTML = '<option disabled selected value="">Choose a doctor</option>';
          if (doctors.length === 0) {
            doctorSelect.innerHTML += '<option disabled>No doctors available</option>';
          } else {
            doctors.forEach(function(doc) {
              doctorSelect.innerHTML += '<option value="' + doc.account_id + '">' + doc.name + ' - ' + doc.specialty + '</option>';
            });
          }
        });
    }
  }
  document.getElementById('bookingDate').addEventListener('change', updateDoctorDropdown);
  document.getElementById('bookingTime').addEventListener('change', updateDoctorDropdown);
});
</script>

<?php if (isset($_SESSION['show_login'])): ?>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
    loginModal.show();
  });
</script>
<?php unset($_SESSION['show_login']); endif; ?>
</body>
</html> 
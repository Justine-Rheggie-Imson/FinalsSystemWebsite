<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}
include '../dbConnect.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
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
        <a class="nav-link active" href="#" onclick="showAdminSection('manageDoctors')">Manage Doctors</a>
        <a class="nav-link" href="#" onclick="showAdminSection('manageClients')">Manage Clients</a>
        <li class="nav-item">
            <a class="nav-link" href="../logout.php">Logout</a>
        </li>
      </nav>
    </nav>
    <!-- Main Content -->
    <main class="col-12 col-md-10 p-4">
      <nav class="navbar navbar-expand-lg navbar-light bg-primary rounded mb-4 px-3">
        <span class="navbar-brand text-white fw-bold">Admin Dashboard</span>
      </nav>
      <h4>Welcome, Admin</h4>
      <div id="manageDoctors" class="admin-section">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 gap-2">
          <h5>Doctor Accounts</h5>
          <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDoctorModal">Add Doctor</button>
        </div>
        <!-- Doctor Table -->
        <div class="table-responsive">
          <table class="table table-bordered align-middle">
            <thead><tr><th>Name</th><th>Email</th><th>Actions</th></tr></thead>
            <tbody>
              <?php
                $doctors = mysqli_query($conn, "SELECT * FROM accounts WHERE role = 'doctor'");
                while ($doc = mysqli_fetch_assoc($doctors)) {
                  echo "<tr>
                          <td>{$doc['fullname']}</td>
                          <td>{$doc['email']}</td>
                          <td>
                            <button class='btn btn-warning btn-sm' onclick='openEditDoctorModal({$doc['id']})'>Edit</button>
                            <a href='functions/delete_doctor.php?id={$doc['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this doctor?\")'>Delete</a>
                          </td>
                        </tr>";
                }
              ?>
            </tbody>
          </table>
        </div>
      </div>
      <!-- Manage Clients Section -->
      <div id="manageClients" class="admin-section" style="display:none;">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 gap-2">
          <h5>Client Accounts</h5>
        </div>
        <!-- Client Table -->
        <div class="table-responsive">
          <table class="table table-bordered align-middle">
            <thead><tr><th>Name</th><th>Email</th><th>Actions</th></tr></thead>
            <tbody>
              <?php
                $clients = mysqli_query($conn, "SELECT * FROM accounts WHERE role = 'client'");
                while ($client = mysqli_fetch_assoc($clients)) {
                  echo "<tr>
                          <td>{$client['fullname']}</td>
                          <td>{$client['email']}</td>
                          <td>
                            <button class='btn btn-warning btn-sm' onclick='openEditDoctorModal({$client['id']})'>Edit</button>
                            <a href='functions/delete_doctor.php?id={$client['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this client?\")'>Delete</a>
                          </td>
                        </tr>";
                }
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </main>
  </div>
</div>

<<!-- Add Doctor Modal -->
<div class="modal fade" id="addDoctorModal" tabindex="-1" aria-labelledby="addDoctorModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content" method="POST" action="functions/add_doctor.php">
      <div class="modal-header">
        <h5 class="modal-title" id="addDoctorModalLabel">Add Doctor</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="text" name="id" placeholder="Doctor ID (Unique)" required class="form-control mb-2">
        <input type="text" name="name" placeholder="Name" required class="form-control mb-2">
        <input type="email" name="email" placeholder="Email" required class="form-control mb-2">
        <input type="password" name="password" placeholder="Password" required class="form-control mb-2">
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-success">Add</button>
      </div>
    </form>
  </div>
</div>

<!-- Edit Doctor Modal -->
<div class="modal fade" id="editDoctorModal" tabindex="-1" aria-labelledby="editDoctorModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content p-4" id="editDoctorModalContent">
      <!-- AJAX-loaded content goes here -->
    </div>
  </div>
</div>

<script>
function showAdminSection(id) {
  document.querySelectorAll('.admin-section').forEach(sec => sec.style.display = 'none');
  document.getElementById(id).style.display = 'block';
}

function openEditDoctorModal(id) {
  fetch('functions/edit_doctor.php?id=' + id)
    .then(response => response.text())
    .then(html => {
      document.getElementById('editDoctorModalContent').innerHTML = html;
      new bootstrap.Modal(document.getElementById('editDoctorModal')).show();

      // Attach AJAX submit handler after content is loaded
      setTimeout(() => {
        const form = document.getElementById('editDoctorForm');
        if (form) {
          form.onsubmit = function(e) {
            e.preventDefault();
            const formData = new FormData(form);
            fetch('functions/edit_doctor.php?id=' + id, {
              method: 'POST',
              body: formData
            })
            .then(response => response.text())
            .then(result => {
              if (result.includes('Update successful') || result.includes('User updated successfully')) {
                document.getElementById('editDoctorModalContent').innerHTML = result;
                setTimeout(() => location.reload(), 1000);
              } else {
                document.getElementById('editDoctorModalContent').innerHTML = result;
              }
            });
          };
        }
      }, 100);
    });
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

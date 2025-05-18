<?php
include 'dbConnect.php';

// Pagination settings
$records_per_page = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $records_per_page;

// Get total number of doctors
$total_query = "SELECT COUNT(*) as count FROM doctors d JOIN accounts a ON d.account_id = a.id WHERE a.role = 'doctor'";
$total_result = $conn->query($total_query);
$total_row = $total_result->fetch_assoc();
$total_records = $total_row['count'];
$total_pages = ceil($total_records / $records_per_page);

// Get doctors for current page
$query = "SELECT d.*, a.email 
          FROM doctors d 
          JOIN accounts a ON d.account_id = a.id 
          WHERE a.role = 'doctor' 
          LIMIT ? OFFSET ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $records_per_page, $offset);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Doctors | HealthServe</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="doctors.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    
    .hero-section {
      padding: 80px 0;
      background-color: #fff;
    }
    .hero-section h1 {
      font-weight: 700;
    }
    .hero-section p {
      font-size: 1.2rem;
    }
    .btn-hero {
      padding: 12px 30px;
      font-weight: bold;
      border-radius: 30px;
    }
    .navbar-brand span {
      font-size: 1.5rem;
      font-weight: bold;
    }
    .navbar {
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .topic-icon {
            font-size: 40px;
            width: 60px;
        }
        .ftr{
    background-color: rgb(231, 245, 248);
}
.ftr .container{
    margin-top: 2.7rem;
}
.ftr a{
    font-size: .8rem;   
    color: rgb(129, 129, 129) !important;
}
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white py-1 ">
  <div class="container-fluid px-3 px-md-4">
    <a class="navbar-brand d-flex align-items-center" href="index.php">
      <div class="d-flex align-items-center">
        <img src="img/imgSoloLogo.png" alt="Logo" class="me-2 " style="height: 50px;">
        <img src="img/imgSoloTitle.png" alt="Title" class="pt-2" style="height: 40px;">
      </div>
    </a>
    
  </div>
</nav>

<!-- Hero Section -->
<section class="hero-section">
  <div class="container">
    <div class="row align-items-center">
      <!-- Text Content -->
      <div class="col-md-6 mb-4 mb-md-0">
        <h1 class="mb-3">Talk to a Doctor now</h1>
        <p class="mb-3">Private doctor consultation. Starts at ₱350.</p>
        <div class="mb-3">
          <span class="me-3">✅ Verified Doctors</span>
          <span class="me-3">💊 E-Prescription</span>
          <span>🔒 Secure & Confidential</span>
        </div>
        <a href="#book" class="btn btn-primary btn-hero">CONSULT NOW</a>
      </div>
      <!-- Image -->
      <div class="col-md-6 text-center">
        <img src="img/imgDoctors.jpg" alt="Doctors" class="img-fluid" style="max-height: 400px;">
      </div>
    </div>
  </div>
</section>

<section class="py-5 doctorSection">
  <div class="container">
    <div class="align-items-center mb-4">
      <h2 class="mb-0">Available Doctors</h2>
    </div>
    
    <div class="table-responsive">
      <table class="table table-hover align-middle">
        <thead class="table-light">
          <tr>
            <th style="width: 80px">Picture</th>
            <th>Name</th>
            <th>Specialty</th>
            <th>Sub Specialty</th>
            <th class="text-center">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php while($doc = $result->fetch_assoc()): ?>
          <tr>
            <td>
              <img src="<?= isset($doc['image']) && $doc['image'] ? '/FinalsWeb/' . htmlspecialchars($doc['image']) : 'img/imgExmpDoc.jpg' ?>" 
                   alt="Doctor Image" 
                   class="rounded-circle" 
                   style="width: 60px; height: 60px; object-fit: cover;">
            </td>
            <td>
              <h6 class="fw-bold mb-0"><?= htmlspecialchars($doc['name']) ?></h6>
            </td>
            <td><?= htmlspecialchars($doc['specialty']) ?></td>
            <td><?= htmlspecialchars($doc['subspecialty']) ?></td>
            <td>
              <div class="d-flex justify-content-center gap-2">
                <a href="doctorInfo.php?id=<?= $doc['account_id'] ?>" class="btn btn-outline-primary btn-sm">View Profile</a>
                <a href="booking.php?doctor_id=<?= $doc['account_id'] ?>" class="btn btn-primary btn-sm">Consult Now</a>
              </div>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <nav aria-label="Page navigation" class="mt-4">
      <ul class="pagination justify-content-center">
        <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
          <a class="page-link" href="?page=<?= $page-1 ?>" <?= $page <= 1 ? 'tabindex="-1" aria-disabled="true"' : '' ?>>Previous</a>
        </li>
        
        <?php for($i = 1; $i <= $total_pages; $i++): ?>
        <li class="page-item <?= $page == $i ? 'active' : '' ?>">
          <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
        </li>
        <?php endfor; ?>
        
        <li class="page-item <?= $page >= $total_pages ? 'disabled' : '' ?>">
          <a class="page-link" href="?page=<?= $page+1 ?>" <?= $page >= $total_pages ? 'tabindex="-1" aria-disabled="true"' : '' ?>>Next</a>
        </li>
      </ul>
    </nav>
  </div>
</section>

<div class="container py-5">
    <h2 class="mb-4 fw-bold text-center">What can I talk to my Doctor about during the online consultation?</h2>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="d-flex align-items-start p-3 border rounded shadow-sm h-100">
                <div class="topic-icon me-3">💊</div>
                <div>
                    <h5 class="fw-bold mb-1">Allergy Concerns</h5>
                    <p class="mb-0 text-muted">Discuss symptoms, triggers, and treatment options for seasonal or food-related allergies.</p>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="d-flex align-items-start p-3 border rounded shadow-sm h-100">
                <div class="topic-icon me-3">🥗</div>
                <div>
                    <h5 class="fw-bold mb-1">Nutrition and Diet</h5>
                    <p class="mb-0 text-muted">Get personalized advice on meal plans, supplements, and maintaining a healthy lifestyle.</p>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="d-flex align-items-start p-3 border rounded shadow-sm h-100">
                <div class="topic-icon me-3">🧠</div>
                <div>
                    <h5 class="fw-bold mb-1">Mental Wellness Support</h5>
                    <p class="mb-0 text-muted">Talk about stress, anxiety, or depression and receive guidance or referral options.</p>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="d-flex align-items-start p-3 border rounded shadow-sm h-100">
                <div class="topic-icon me-3">💉</div>
                <div>
                    <h5 class="fw-bold mb-1">Immunization Planning</h5>
                    <p class="mb-0 text-muted">Understand vaccine schedules, requirements for travel, and protection against disease.</p>
                </div>
            </div>
        </div>
    </div>
</div>


<footer class="pt-4 border-top mt-5 ftr">
  <div class="container">
    <div class="row g-4 text-center text-md-start">
      <!-- For Patients -->
      <div class="col-12 col-md-3">
        <h6 class="fw-bold mb-3">For Patients</h6>
        <ul class="list-unstyled small">
          <li class="mb-2"><a href="doctors.php" class="text-dark text-decoration-none">Find a Doctor</a></li>
          <li class="mb-2"><a href="specialities.php" class="text-dark text-decoration-none">Specialties</a></li>
          <li class="mb-2"><a href="conditions.php" class="text-dark text-decoration-none">Conditions</a></li>
          <li class="mb-2"><a href="booking/booking.php" class="text-dark text-decoration-none">Booking</a></li>
        </ul>
      </div>

      <!-- General -->
      <div class="col-12 col-md-3">
        <h6 class="fw-bold mb-3">General</h6>
        <ul class="list-unstyled small">
        <li class="mb-2"><a href="index.php" class="text-dark text-decoration-none">Home</a></li>
          <li class="mb-2"><a href="about.php" class="text-dark text-decoration-none">About Us</a></li>
          <li class="mb-2"><a href="terms.php" class="text-dark text-decoration-none">Terms & Conditions</a></li>
          <li class="mb-2"><a href="privacy.php" class="text-dark text-decoration-none">Privacy Policy</a></li>
        </ul>
      </div>

      <!-- Social Media -->
      <div class="col-12 col-md-3">
        <h6 class="fw-bold mb-3">Social Media</h6>
        <div class="d-flex justify-content-center justify-content-md-start gap-3">
          <a href="#" class="text-secondary"><i class="bi bi-twitter fs-5"></i></a>
          <a href="#" class="text-secondary"><i class="bi bi-facebook fs-5"></i></a>
          <a href="#" class="text-secondary"><i class="bi bi-instagram fs-5"></i></a>
          <a href="#" class="text-secondary"><i class="bi bi-linkedin fs-5"></i></a>
          <a href="#" class="text-secondary"><i class="bi bi-youtube fs-5"></i></a>
        </div>
      </div>
    </div>

    <!-- Bottom Text -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center text-center text-md-start py-4 border-top mt-4">
      <small class="text-muted mb-2 mb-md-0">Copyright © 2025 All Rights Reserved.</small>
      <a href="#" class="btn btn-outline-dark btn-sm">BACK TO TOP</a>
    </div>
  </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

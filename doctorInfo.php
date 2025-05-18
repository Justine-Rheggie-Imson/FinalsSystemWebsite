<?php
include 'dbConnect.php';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $conn->prepare("SELECT d.*, a.email FROM doctors d JOIN accounts a ON d.account_id = a.id WHERE d.account_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$doc = $result->fetch_assoc();
if (!$doc) {
    echo "Doctor not found.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($doc['name']) ?> | HealthServe</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    .ftr{
    background-color: rgb(231, 245, 248);
    margin-top: 150px!important;
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
<body class="bg-light ">
<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm mb-4">
  <div class="container-fluid px-3 px-md-4">
    <a class="navbar-brand d-flex align-items-center" href="index.php">
      <div class="d-flex align-items-center">
        <img src="img/imgSoloLogo.png" alt="Logo" class="me-2" style="height: 50px;">
        <img src="img/imgSoloTitle.png" alt="Title" class="pt-2" style="height: 40px;">
      </div>
    </a>
    
  </div>
</nav>
<div class="container">
  <a href="index.php" class="btn btn-outline-secondary mb-4">← Back to Doctors</a>
  <div class="row doc-info-section g-4">
    <!-- Left Column: Main Info -->
    <div class="col-12 col-lg-8 mb-4 mb-lg-0">
      <div class="row">
        <!-- Doctor Image -->
        <div class="col-12 col-md-5 text-center mb-4 mb-md-0">
          <img src="<?= isset($doc['image']) && $doc['image'] ? '/FinalsWeb/' . htmlspecialchars($doc['image']) : 'img/imgExmpDoc.jpg' ?>" 
               alt="<?= htmlspecialchars($doc['name']) ?>" 
               class="doc-image img-fluid rounded shadow-sm mx-auto"
               style="max-width: 250px; height: auto;">
        </div>
        <!-- Doctor Info -->
        <div class="col-12 col-md-7">
          <h2 class="fw-bold mb-4 text-center text-md-start"><?= htmlspecialchars($doc['name']) ?></h2>
          <div class="row g-3">
            <div class="col-6">
              <div class="doc-label fw-semibold text-muted mb-1">Specialty</div>
              <div class="doc-value mb-3"><?= htmlspecialchars($doc['specialty']) ?></div>
            </div>
            <div class="col-6">
              <div class="doc-label fw-semibold text-muted mb-1">Subspecialty</div>
              <div class="doc-value mb-3"><?= htmlspecialchars($doc['subspecialty']) ?></div>
            </div>
            <div class="col-6">
              <div class="doc-label fw-semibold text-muted mb-1">Years of Experience</div>
              <div class="doc-value mb-3"><?= htmlspecialchars($doc['experience']) ?></div>
            </div>
            <div class="col-6">
              <div class="doc-label fw-semibold text-muted mb-1">Consultation Fee</div>
              <div class="doc-value mb-3"><?= htmlspecialchars($doc['fee']) ?></div>
            </div>
            <div class="col-6">
              <div class="doc-label fw-semibold text-muted mb-1">Availability</div>
              <div class="doc-value mb-3">
                <?= !empty($doc['in_person']) ? 'In-Person' : '' ?>
                <?= !empty($doc['in_person']) && !empty($doc['online']) ? ' / ' : '' ?>
                <?= !empty($doc['online']) ? 'Online' : '' ?>
              </div>
            </div>
            <div class="col-6">
              <div class="doc-label fw-semibold text-muted mb-1">Medical Affiliation</div>
              <div class="doc-value mb-3"><?= htmlspecialchars($doc['affiliation']) ?></div>
            </div>
          </div>
          <div class="mt-4">
            <div class="doc-label fw-semibold text-muted mb-2">About the Doctor</div>
            <div class="doc-value"><?= htmlspecialchars($doc['bio']) ?></div>
          </div>
        </div>
      </div>
    </div>
    <!-- Right Column: Education & Certifications -->
    <div class="col-12 col-lg-4 mt-4 mt-lg-0">
      <div class="card shadow-sm mb-4">
        <div class="card-body">
          <h5 class="card-title fw-bold mb-3">Education</h5>
          <p class="card-text mb-0"><?= htmlspecialchars($doc['education']) ?></p>
        </div>
      </div>
      <div class="card shadow-sm">
        <div class="card-body">
          <h5 class="card-title fw-bold mb-3">Certifications</h5>
          <p class="card-text mb-0"><?= htmlspecialchars($doc['certifications']) ?></p>
        </div>
      </div>
    </div>
  </div>
  <!-- Book Button Below Full Row -->
  <div class="text-center mt-5">
    <a href="booking/booking.php" class="btn btn-primary px-5 py-3 fw-semibold">Book Online Consultation</a>
  </div>
</div>

<style>
  .doc-info-section {
    background-color: white;
    border-radius: 10px;
    padding: 2rem;
    box-shadow: 0 0 15px rgba(0,0,0,0.05);
  }
  
  .doc-label {
    font-size: 0.9rem;
  }
  
  .doc-value {
    font-size: 1rem;
  }

  @media (max-width: 768px) {
    .doc-info-section {
      padding: 1.5rem;
    }
    
    .doc-image {
      max-width: 200px !important;
    }
  }
</style>

<!-- Footer -->
<footer class="pt-4 border-top mt-5 ftr">
  <div class="container">
    <div class="row g-4 text-center text-md-start">
      <!-- For Patients -->
      <div class="col-12 col-md-3">
        <h6 class="fw-bold mb-3">For Patients</h6>
        <ul class="list-unstyled small">
          <li class="mb-2"><a href="#" class="text-dark text-decoration-none">Find a Doctor</a></li>
          <li class="mb-2"><a href="#" class="text-dark text-decoration-none">Hospitals</a></li>
          <li class="mb-2"><a href="#" class="text-dark text-decoration-none">HMOs</a></li>
          <li class="mb-2"><a href="#" class="text-dark text-decoration-none">Specialties</a></li>
          <li class="mb-2"><a href="#" class="text-dark text-decoration-none">Cities</a></li>
          <li class="mb-2"><a href="#" class="text-dark text-decoration-none">Societies</a></li>
          <li class="mb-2"><a href="#" class="text-dark text-decoration-none">Conditions</a></li>
          <li class="mb-2"><a href="#" class="text-dark text-decoration-none">Services</a></li>
          <li class="mb-2"><a href="#" class="text-dark text-decoration-none">Online Consultation</a></li>
        </ul>
      </div>
      <!-- For Doctors -->
      <div class="col-12 col-md-3">
        <h6 class="fw-bold mb-3">For Doctors</h6>
        <ul class="list-unstyled small">
          <li class="mb-2"><a href="#" class="text-dark text-decoration-none">List your Practice</a></li>
          <li class="mb-2"><a href="#" class="text-dark text-decoration-none">Support Center</a></li>
          <li class="mb-2"><a href="#" class="text-dark text-decoration-none">Dashboard Login</a></li>
        </ul>
      </div>
      <!-- General -->
      <div class="col-12 col-md-3">
        <h6 class="fw-bold mb-3">General</h6>
        <ul class="list-unstyled small">
          <li class="mb-2"><a href="#" class="text-dark text-decoration-none">About Us</a></li>
          <li class="mb-2"><a href="#" class="text-dark text-decoration-none">Terms & Conditions</a></li>
          <li class="mb-2"><a href="#" class="text-dark text-decoration-none">Privacy Policy</a></li>
          <li class="mb-2"><a href="#" class="text-dark text-decoration-none">Partners</a></li>
          <li class="mb-2"><a href="#" class="text-dark text-decoration-none">Corporate</a></li>
          <li class="mb-2"><a href="#" class="text-dark text-decoration-none">Blog</a></li>
          <li class="mb-2"><a href="#" class="text-dark text-decoration-none">Podcast</a></li>
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

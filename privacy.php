<?php
session_start();
include 'dbConnect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Privacy Policy - HealthServe</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>.ftr{
    background-color: rgb(231, 245, 248);
}
.ftr .container{
    margin-top: 2.7rem;
}
.ftr a{
    font-size: .8rem;   
    color: rgb(129, 129, 129) !important;
}</style>
</head>
<body>
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white py-1 shadow-sm">
    <div class="container-fluid px-3 px-md-4">
        <a class="navbar-brand d-flex align-items-center" href="index.php">
            <div class="d-flex align-items-center">
                <img src="img/imgSoloLogo.png" alt="Logo" class="me-2" style="height: 50px;">
                <img src="img/imgSoloTitle.png" alt="Title" class="pt-2" style="height: 40px;">
            </div>
        </a>
        
    </div>
</nav>
<div class="container py-5">
  <h1 class="mb-4 text-center text-md-start">Privacy Policy</h1>
  <div class="row justify-content-center">
    <div class="col-12 col-md-10 col-lg-8">
       <p><strong>1. Introduction</strong><br>
      We value your privacy and are committed to protecting your personal information. This Privacy Policy outlines how we collect, use, and safeguard your data when you use our healthcare platform.</p>

      <p><strong>2. Information We Collect</strong><br>
      We may collect personal information such as your name, contact details, medical history, and appointment records when you register or use our services. We may also collect technical data like IP addresses, browser type, and usage patterns.</p>

      <p><strong>3. How We Use Your Information</strong><br>
      Your information is used to provide healthcare services, process appointments, improve user experience, and comply with legal obligations. We may also use anonymized data for research and service improvement.</p>

      <p><strong>4. Data Sharing and Disclosure</strong><br>
      We do not sell or rent your personal information. We may share it with licensed healthcare providers involved in your care, and with third-party service providers who help us operate our platform securely and efficiently.</p>

      <p><strong>5. Security of Your Information</strong><br>
      We implement strong security measures to protect your data from unauthorized access, disclosure, alteration, or destruction. However, no internet transmission is completely secure, so we cannot guarantee absolute security.</p>

      <p><strong>6. Your Rights</strong><br>
      You have the right to access, update, or delete your personal information. You may also request restrictions on how your data is processed or withdraw your consent at any time, subject to applicable laws.</p>

      <p><strong>7. Cookies and Tracking</strong><br>
      Our website may use cookies to improve user experience and analyze traffic. You can choose to disable cookies in your browser settings, but some features may not function properly.</p>

      <p><strong>8. Changes to This Policy</strong><br>
      We reserve the right to update this Privacy Policy at any time. We will notify you of significant changes and post the revised policy on our website with the updated effective date.</p>

      <p><strong>9. Contact Us</strong><br>
      If you have any questions about this Privacy Policy or how we handle your personal data, please contact us via the support section of our website.</p>
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
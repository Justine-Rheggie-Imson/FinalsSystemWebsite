<?php
session_start();
include 'dbConnect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Terms & Conditions - HealthServe</title>
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
  <h1 class="mb-4 text-center text-md-start">Terms & Conditions</h1>
  <div class="row justify-content-center">
    <div class="col-12 col-md-10 col-lg-8">
       <p><strong>1. Acceptance of Terms</strong><br>
      By accessing or using our healthcare platform, you agree to be bound by these Terms & Conditions and our Privacy Policy. If you do not agree, please do not use our services.</p>

      <p><strong>2. User Accounts</strong><br>
      To access certain features, you must register for an account. You are responsible for maintaining the confidentiality of your login credentials and all activities that occur under your account.</p>

      <p><strong>3. Use of Services</strong><br>
      Our platform connects patients with licensed healthcare professionals for virtual and in-person consultations. You agree to use these services responsibly and not for emergencies. In case of a medical emergency, call your local emergency number immediately.</p>

      <p><strong>4. Medical Disclaimer</strong><br>
      The information provided on this platform is not a substitute for professional medical advice, diagnosis, or treatment. Always seek the advice of a qualified healthcare provider with any questions you may have regarding a medical condition.</p>

      <p><strong>5. Appointment Cancellations</strong><br>
      You may cancel or reschedule appointments within the timeframe allowed in our policy. Missed appointments without proper notice may incur fees or restrictions.</p>

      <p><strong>6. Privacy and Data Security</strong><br>
      We are committed to protecting your personal health information. Please refer to our Privacy Policy to learn how your data is collected, used, and protected.</p>

      <p><strong>7. Modifications to Terms</strong><br>
      We reserve the right to update or modify these Terms & Conditions at any time. Continued use of the platform after changes are posted constitutes acceptance of those changes.</p>

      <p><strong>8. Contact Us</strong><br>
      For any questions about these Terms & Conditions, please contact us through the support section of our website.</p>
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
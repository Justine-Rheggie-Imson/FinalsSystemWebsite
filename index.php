<?php
session_start(); // <--- THIS IS CRUCIAL, MUST BE AT THE VERY TOP
include 'dbConnect.php';
$doctorQuery = $conn->query("SELECT d.account_id, d.name, d.specialty, d.image FROM doctors d JOIN accounts a ON d.account_id = a.id WHERE a.role = 'doctor' LIMIT 3");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>HealthServe</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm p-2">
  <div class="container-fluid mx-4">
    <!-- Logo section -->
    <div class="d-flex align-items-center">
      <?php if(isset($_SESSION['role'])): ?>
        <?php if($_SESSION['role'] === 'client'): ?>
          <a href="dashboardClient.php">
        <?php elseif($_SESSION['role'] === 'doctor'): ?>
          <a href="dashboard/dashboardDoctor.php">
        <?php elseif($_SESSION['role'] === 'admin'): ?>
          <a href="dashboard/dashboardAdmin.php">
        <?php endif; ?>
      <?php else: ?>
        <a href="index.php">
      <?php endif; ?>
        <div class="d-flex align-items-center">
          <img src="img/imgSoloLogo.png" alt="Logo" class="me-2 " style="height: 70px;">
          <img src="img/imgSoloTitle.png" alt="Title" class="pt-3" style="height: 60px;">
        </div>
      </a>
    </div>

    <!-- Toggler for mobile view -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Navigation items -->
    <div class="collapse navbar-collapse justify-content-end" id="navMenu">
      <ul class="navbar-nav">
        <?php if(isset($_SESSION['user_id'])): ?>
          <!-- Logged in user navigation -->
          <li class="nav-item"><a class="nav-link" href="doctors.php">Doctors</a></li>
          <li class="nav-item"><a class="nav-link" href="conditions.php">Conditions</a></li>
          <?php if($_SESSION['role'] === 'client'): ?>
            <li class="nav-item"><a class="nav-link" href="dashboard/dashboardClient.php">Dashboard</a></li>
          <?php elseif($_SESSION['role'] === 'doctor'): ?>
            <li class="nav-item"><a class="nav-link" href="dashboard/dashboardDoctor.php">Dashboard</a></li>
          <?php elseif($_SESSION['role'] === 'admin'): ?>
            <li class="nav-item"><a class="nav-link" href="dashboard/dashboardAdmin.php">Dashboard</a></li>
          <?php endif; ?>
          <li class="nav-item">
            <a class="nav-link btn-danger" href="logout.php">Logout</a>
          </li>
          <li class="nav-item">
            <a class="btn btn-primary ms-3" href="booking/booking.php">Make a Booking</a>
          </li>
          
        <?php else: ?>
          <!-- Guest navigation -->
          <li class="nav-item"><a class="nav-link" href="doctors.php">Doctors</a></li>
          <li class="nav-item"><a class="nav-link" href="conditions.php">Conditions</a></li>
          <li class="nav-item">
            <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#loginModal">Login</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#signupModal">Sign Up</a>
          </li>
          <li class="nav-item">
            <a class="btn btn-primary ms-3" href="booking/booking.php">Make a Booking</a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>


<!-- Hero Section -->
<section class="fullscreen-hero">
  <div class="container">
    <h1 class="display-4 fw-bold">Your Health, One Tap Away</h1>
    <p class="lead">Book appointments, consult online, and access your medical info easily.</p>
    
    <!-- Search bar -->
    <form class="row justify-content-center mt-4" autocomplete="off" style="position:relative;">
      <div class="col-md-6 position-relative">
        <input type="text" id="heroSearch" class="form-control" placeholder="Search for doctors..." autocomplete="off" />
        <div id="searchDropdown" class="list-group position-absolute w-100" style="z-index:1000; display:none;"></div>
      </div>
    </form>

    <!-- Booking Instructions Box -->
    <div class="mt-4 text-center">
      <div class="alert alert-info d-inline-block shadow-sm px-4 py-3" role="alert" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#bookingInstructionModal">
        📋 How to Book an Appointment? Click here to learn more.
      </div>
    </div>
  </div>
</section>


<!-- Doctors Section -->
<section class="doctorSec" >
  <div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="mb-0">Available Doctors</h2>
      <a href="doctors.php" class="btn btn-light rounded-4 text-primary mainBtnSection">View More Doctors</a>
    </div>
    <div class="row g-4">
      <?php while($doc = $doctorQuery->fetch_assoc()): ?>
      <div class="col-md-4">
        <div class="card shadow-sm p-3 d-flex align-items-center" style="min-height: 160px;">
          <div class="d-flex align-items-center w-100 ms-5">
            <img src="<?= isset($doc['image']) && $doc['image'] ? '/FinalsWeb/' . htmlspecialchars($doc['image']) : 'img/imgExmpDoc.jpg' ?>" alt="Doctor Image" class="rounded-circle me-3" style="width: 80px; height: 80px; object-fit: cover;">
            <div class="flex-grow-1">
              <h6 class="fw-bold mb-1 ms-2"><?= htmlspecialchars($doc['name'] ?? 'Doctor') ?></h6>
              <p class="mb-2 text-muted ms-2" style="font-size: 0.9rem;"><?= htmlspecialchars($doc['specialty']) ?></p>
            </div>
          </div>
          <div class="d-flex gap-2 mt-3">
                <a href="doctorInfo.php?id=<?= $doc['account_id'] ?>" class="btn btn-outline-primary btn-sm">View Profile</a>
                <a href="#" class="btn btn-primary btn-sm">Consult Now</a>
              </div>
        </div>
      </div>
      <?php endwhile; ?>
    </div>
  </div>
</section>

<!-- Common Conditions Section -->
<section class="py-5 bg-light">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="mb-0">Common Conditions</h2>
      <a href="conditions.php" class="btn btn-light rounded-4 text-primary mainBtnSection">Browse More Conditions</a>
    </div>
    <div class="row g-3">

      <div class="col-md-3">
        <a href="conditionDetail.php?condition=arthritis" class="text-decoration-none text-dark">
          <div class="card shadow-sm h-100 card-hover" style="width: 16rem;">
            <img src="conditionsImg/arthritis.png" alt="Arthritis" class="p-3 m-auto" style="width: 170px; ">
            <div class="card-body text-center">
              <h5 class="card-title">Arthritis</h5>
            </div>
          </div>
        </a>
      </div>

      <div class="col-md-3">
        <a href="conditionDetail.php?condition=backpain" class="text-decoration-none text-dark">
          <div class="card shadow-sm h-100 card-hover" style="width: 16rem;">
            <img src="conditionsImg/backpain.png" alt="Back Pain" class="p-3 m-auto" style="width: 170px; ">
            <div class="card-body text-center">
              <h5 class="card-title">Back Pain</h5>
            </div>
          </div>
        </a>
      </div>

      <div class="col-md-3">
        <a href="conditionDetail.php?condition=diabetes" class="text-decoration-none text-dark">
          <div class="card shadow-sm h-100 card-hover" style="width: 16rem;">
            <img src="conditionsImg/diabetes.png" alt="Diabetes" class="p-3 m-auto" style="width: 170px; ">
            <div class="card-body text-center">
              <h5 class="card-title">Diabetes</h5>
            </div>
          </div>
        </a>
      </div>

      <div class="col-md-3">
        <a href="conditionDetail.php?condition=headache" class="text-decoration-none text-dark">
          <div class="card shadow-sm h-100 card-hover" style="width: 16rem;">
            <img src="conditionsImg/headache.png" alt="Headache" class="p-3 m-auto" style="width: 170px; ">
            <div class="card-body text-center">
              <h5 class="card-title">Headache</h5>
            </div>
          </div>
        </a>
      </div>

      <div class="col-md-3">
        <a href="conditionDetail.php?condition=diarrhea" class="text-decoration-none text-dark">
          <div class="card shadow-sm h-100 card-hover" style="width: 16rem;">
            <img src="conditionsImg/diarhea.png" alt="Diarrhea" class="p-3 m-auto" style="width: 170px; ">
            <div class="card-body text-center">
              <h5 class="card-title">Diarrhea</h5>
            </div>
          </div>
        </a>
      </div>

      <div class="col-md-3">
        <a href="conditionDetail.php?condition=pregnancy" class="text-decoration-none text-dark">
          <div class="card shadow-sm h-100 card-hover" style="width: 16rem;">
            <img src="conditionsImg/pregnancy.png" alt="Pregnancy" class="p-3 m-auto" style="width: 170px; ">
            <div class="card-body text-center">
              <h5 class="card-title">Pregnancy</h5>
            </div>
          </div>
        </a>
      </div>

      <div class="col-md-3">
        <a href="conditionDetail.php?condition=cold" class="text-decoration-none text-dark">
          <div class="card shadow-sm h-100 card-hover" style="width: 16rem;">
            <img src="conditionsImg/cold n flu.png" alt="Cold and Flu" class="p-3 m-auto" style="width: 170px; ">
            <div class="card-body text-center">
              <h5 class="card-title">Cold and Flu</h5>
            </div>
          </div>
        </a>
      </div>

      <div class="col-md-3">
        <a href="conditionDetail.php?condition=allergies" class="text-decoration-none text-dark">
          <div class="card shadow-sm h-100 card-hover" style="width: 16rem;">
            <img src="conditionsImg/allergic.png" alt="Allergies" class="p-3 m-auto" style="width: 170px; ">
            <div class="card-body text-center">
              <h5 class="card-title">Allergies</h5>
            </div>
          </div>
        </a>
      </div>

     
      
      <!-- Add more conditions if needed -->
    </div>
  </div>
</section>

<!-- Services Section -->
<section class="py-5 serviceSec" >
<div class="container">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Our Services</h2>
    <a href="specialties.php" class="btn btn-light rounded-4 text-primary mainBtnSection">Browse Doctor Specialties</a>
  </div>

  <div class="row g-3">
  <div class="col-md-3">
        <a href="serviceDetail.php?service=cardiology" class="text-decoration-none text-dark">
          <div class="card shadow-sm h-100 card-hover" style="width: 16rem;">
            <img src="servicesImg/cardiology heart.png" alt="" class="p-3 m-auto" style="width: 170px; ">
            <div class="card-body text-center">
              <h5 class="card-title">Cardiology</h5>
            </div>
          </div>
        </a>
      </div>

      <div class="col-md-3">
        <a href="serviceDetail.php?service=checkup" class="text-decoration-none text-dark">
          <div class="card shadow-sm h-100 card-hover" style="width: 16rem;">
            <img src="servicesImg/check up.png" alt="" class="p-3 m-auto" style="width: 170px; ">
            <div class="card-body text-center">
              <h5 class="card-title">Check Up</h5>
            </div>
          </div>
        </a>
      </div>
      <div class="col-md-3">
        <a href="serviceDetail.php?service=gynecology" class="text-decoration-none text-dark">
          <div class="card shadow-sm h-100 card-hover" style="width: 16rem;">
            <img src="servicesImg/gynecologist reproductive.png" alt="" class="p-3 m-auto" style="width: 170px; ">
            <div class="card-body text-center">
              <h5 class="card-title">Gynecology</h5>
            </div>
          </div>
        </a>
      </div>
      <div class="col-md-3">
        <a href="serviceDetail.php?service=anesthesiology" class="text-decoration-none text-dark">
          <div class="card shadow-sm h-100 card-hover" style="width: 16rem;">
            <img src="servicesImg/anesthesiologist anestesia.png" alt="" class="p-3 m-auto" style="width: 170px; ">
            <div class="card-body text-center">
              <h5 class="card-title">Anesthesiology </h5>
            </div>
          </div>
        </a>
      </div>
      <div class="col-md-3">
        <a href="serviceDetail.php?service=dermatology" class="text-decoration-none text-dark">
          <div class="card shadow-sm h-100 card-hover" style="width: 16rem;">
            <img src="servicesImg/dermatology.png" alt="" class="p-3 m-auto" style="width: 170px; ">
            <div class="card-body text-center">
              <h5 class="card-title">Dermatology</h5>
            </div>
          </div>
        </a>
      </div>
      <div class="col-md-3">
        <a href="serviceDetail.php?service=kidneynephrologist" class="text-decoration-none text-dark">
          <div class="card shadow-sm h-100 card-hover" style="width: 16rem;">
            <img src="servicesImg/kidney nephrologist.png" alt="" class="p-3 m-auto" style="width: 170px; ">
            <div class="card-body text-center">
              <h5 class="card-title">Kidney Nephrologist</h5>
            </div>
          </div>
        </a>
      </div>
      <div class="col-md-3">
        <a href="serviceDetail.php?service=neurologist" class="text-decoration-none text-dark">
          <div class="card shadow-sm h-100 card-hover" style="width: 16rem;">
            <img src="servicesImg/neurologist brain.png" alt="" class="p-3 m-auto" style="width: 170px; ">
            <div class="card-body text-center">
              <h5 class="card-title">Neurologist</h5>
            </div>
          </div>
        </a>
      </div>
      <div class="col-md-3">
        <a href="serviceDetail.php?service=pulmonology" class="text-decoration-none text-dark">
          <div class="card shadow-sm h-100 card-hover" style="width: 16rem;">
            <img src="servicesImg/pulmonology lungs.png" alt="" class="p-3 m-auto" style="width: 170px; ">
            <div class="card-body text-center">
              <h5 class="card-title">Pulmonology</h5>
            </div>
          </div>
        </a>
      </div>
</div>

</section>

<!-- Footer -->
<footer class="pt-4 border-top mt-5 ftr">
  <div class="container">
    <div class="row g-4 text-center text-md-start">
      <!-- For Patients -->
      <div class="col-6 col-md-3">
        <h6 class="fw-bold mb-3">For Patients</h6>
        <ul class="list-unstyled small">
          <li class="mb-2"><a href="doctors.php" class="text-dark text-decoration-none">Find a Doctor</a></li>
          <li class="mb-2"><a href="specialities.php" class="text-dark text-decoration-none">Specialties</a></li>
          <li class="mb-2"><a href="conditions.php" class="text-dark text-decoration-none">Conditions</a></li>
          <li class="mb-2"><a href="booking/booking.php" class="text-dark text-decoration-none">Booking</a></li>
        </ul>
      </div>

      <!-- General -->
      <div class="col-6 col-md-3">
        <h6 class="fw-bold mb-3">General</h6>
        <ul class="list-unstyled small">
        <li class="mb-2"><a href="index.php" class="text-dark text-decoration-none">Home</a></li>
          <li class="mb-2"><a href="about.php" class="text-dark text-decoration-none">About Us</a></li>
          <li class="mb-2"><a href="terms.php" class="text-dark text-decoration-none">Terms & Conditions</a></li>
          <li class="mb-2"><a href="privacy.php" class="text-dark text-decoration-none">Privacy Policy</a></li>
        </ul>
      </div>

      <!-- Social Media -->
      <div class="col-6 col-md-3">
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



<!-- Login Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <div class="modal-body p-4">
        <!-- Logo -->
        <div class="text-center mb-4">
          <img src="img/imgSoloLogo.png" alt="HealthServe Logo" style="height: 90px;">
        </div>

        <h5 class="text-center fw-bold mb-3">Login to HealthServe</h5>

        <?php if (isset($_SESSION['error'])): ?>
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        <?php endif; ?>

        <!-- Login Form -->
        <?php if (isset($_SESSION['login_message'])): ?>
          <div class="alert alert-warning text-center mb-3" id="loginMessageAlert"><?= $_SESSION['login_message']; unset($_SESSION['login_message']); ?></div>
        <?php endif; ?>
        <form action="login.php" method="POST">
          <!-- Form fields -->
          <div class="mb-3">
            <label for="loginEmail" class="form-label">Email address</label>
            <input type="email" class="form-control" name="email" id="loginEmail" placeholder="Enter your email" required>
          </div>
          <div class="mb-2">
            <label for="loginPassword" class="form-label">Password</label>
            <input type="password" class="form-control" name="password" id="loginPassword" placeholder="Enter your password" required>
          </div>

          <!-- Forgot password link -->
          <div class="d-flex justify-content-end mb-3">
            <a href="#" class="text-decoration-none small text-primary" data-bs-toggle="modal" data-bs-target="#forgotPasswordModal" data-bs-dismiss="modal">Forgot Password?</a>
          </div>

          <div class="d-grid">
            <button type="submit" class="btn btn-primary">Login</button>
          </div>
        </form>

        <!-- Sign up prompt -->
        <div class="text-center mt-4">
          <p class="mb-0">Don't have an account? <a href="#" class="text-decoration-none text-primary" data-bs-toggle="modal" data-bs-target="#signupModal" data-bs-dismiss="modal">Sign up now</a></p>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Forgot Password Modal -->
<div class="modal fade" id="forgotPasswordModal" tabindex="-1" aria-labelledby="forgotPasswordLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="forgotPasswordLabel">Forgot Password</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form action="forgotPass.php" method="POST">
        <div class="modal-body">

          <div class="mb-3">
            <label for="fp_email" class="form-label">Email address</label>
            <input type="email" class="form-control" id="fp_email" name="email" required>
          </div>

          <div class="mb-3">
            <label for="fp_animal" class="form-label">What is your favorite animal?</label>
            <input type="text" class="form-control" id="fp_animal" name="security_answer" required>
          </div>

          <div class="mb-3">
            <label for="fp_new_password" class="form-label">New Password</label>
            <input type="password" class="form-control" id="fp_new_password" name="new_password" required>
          </div>

          <div class="mb-3">
            <label for="fp_confirm_password" class="form-label">Confirm New Password</label>
            <input type="password" class="form-control" id="fp_confirm_password" name="confirm_password" required>
          </div>

        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Reset Password</button>
        </div>
      </form>

    </div>
  </div>
</div>


<!-- Sign-Up Modal -->
<div class="modal fade" id="signupModal" tabindex="-1" aria-labelledby="signupModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <div class="modal-body p-4">
        
        <!-- Logo Placeholder -->
        <div class="text-center mb-4">
          <img src="img/imgSoloLogo.png" alt="HealthServe Logo" style="height: 90px;">
        </div>

        <h5 class="text-center fw-bold mb-3" id="signupModalLabel">Create Your Account</h5>

        <!-- Sign-Up Form -->
        <form action="signup.php" method="POST">
          <div class="mb-3">
            <label for="signupFullName" class="form-label">Full Name</label>
            <input type="text" class="form-control" id="signupFullName" name="full_name" placeholder="Enter your full name" required>
          </div>
          <div class="mb-3">
            <label for="signupEmail" class="form-label">Email address</label>
            <input type="email" class="form-control" id="signupEmail" name="email" placeholder="Enter your email" required>
          </div>
          <div class="mb-3">
            <label for="signupPassword" class="form-label">Password</label>
            <input type="password" class="form-control" id="signupPassword" name="password" placeholder="Enter your password" required>
          </div>
          <div class="mb-1">
            <label for="signupAnimal" class="form-label">Favorite Animal</label>
            <input type="text" class="form-control" id="signupAnimal" name="animal" placeholder="e.g., Dog, Cat" required>
            <small class="form-text text-muted">For authentication</small>
          </div>

          <!-- Submit Button -->
          <div class="d-grid mt-3">
            <button type="submit" class="btn btn-primary w-100">Sign Up</button>
          </div>
        </form>

        <!-- Optional close button at the bottom -->
        <div class="text-center mt-3">
          <button type="button" class="btn btn-link text-muted" data-bs-dismiss="modal">Already have an account? Log in</button>
        </div>

      </div>
    </div>
  </div>
</div>
<!-- Registration Success Modal -->
<div class="modal fade" id="registerSuccessModal" tabindex="-1" aria-labelledby="registerSuccessLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <div class="modal-body p-4 text-center">
        <!-- Success Icon -->
        <div class="mb-4">
          <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="#28a745" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
          </svg>
        </div>
        
        <h5 class="fw-bold mb-3" id="registerSuccessLabel">Registration Successful!</h5>
        <p class="mb-4">Your account has been created successfully. You can now login with your credentials.</p>
        
        <div class="d-grid">
          <button type="button" class="btn btn-success" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#loginModal">
            Proceed to Login
          </button>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Booking Instruction Modal -->
<div class="modal fade" id="bookingInstructionModal" tabindex="-1" aria-labelledby="bookingInstructionLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="bookingInstructionLabel">How to Book an Appointment</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

        <!-- Step 1 -->
        <div class="mb-4">
          <h6 class="fw-bold">1. Select a Doctor</h6>
          <p>Browse through our list of qualified healthcare professionals and select the doctor that suits your needs. You can filter by specialization, experience, and availability.</p>
        </div>

        <!-- Step 2 -->
        <div class="mb-4">
          <h6 class="fw-bold">2. Fill Out the Form</h6>
          <p>After choosing a doctor, you'll be asked to fill out a short booking form with your personal and health details, along with your preferred date and time for consultation.</p>
        </div>

        <!-- Step 3 -->
        <div class="mb-4">
          <h6 class="fw-bold">3. Make a Payment</h6>
          <p>Securely make your payment through our integrated payment gateways. We accept credit/debit cards and popular online wallets.</p>
        </div>

        <!-- Step 4 -->
        <div>
          <h6 class="fw-bold">4. Confirmation</h6>
          <p>Once your booking is complete, you will receive a confirmation via email and SMS. You can also view it on your HealthServe account dashboard.</p>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<?php if (isset($_SESSION['show_login'])): ?>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
    loginModal.show();
  });
</script>
<?php unset($_SESSION['show_login']); endif; ?>
<?php if (isset($_SESSION['show_register_success'])): ?>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Show success modal
    var successModal = new bootstrap.Modal(document.getElementById('registerSuccessModal'));
    successModal.show();
    
    // When success modal is closed, open login modal
    document.getElementById('registerSuccessModal').addEventListener('hidden.bs.modal', function () {
      var loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
      loginModal.show();
    });
  });
</script>
<?php unset($_SESSION['show_register_success']); endif; ?>

<?php if (isset($_SESSION['signup_success'])): ?>
<script>
  window.addEventListener('DOMContentLoaded', function() {
    window.alert('Registration successful! You can now log in.');
  });
</script>
<?php unset($_SESSION['signup_success']); endif; ?>

<script>
const searchInput = document.getElementById('heroSearch');
const dropdown = document.getElementById('searchDropdown');
let lastSuggestions = [];

searchInput.addEventListener('input', function() {
  const query = this.value.trim();
  if (query.length < 2) { // Only search if 2+ chars
    dropdown.style.display = 'none';
    dropdown.innerHTML = '';
    lastSuggestions = [];
    return;
  }
  fetch('searchSuggest.php?q=' + encodeURIComponent(query))
    .then(res => res.json())
    .then(data => {
      if (data.debug) {
        console.log('Search debug:', data.debug);
      }
      const suggestions = Array.isArray(data) ? data : (Array.isArray(data.suggestions) ? data.suggestions : []);
      lastSuggestions = suggestions; // Save for Enter key
      if (!Array.isArray(suggestions)) {
        dropdown.style.display = 'none';
        return;
      }
      dropdown.innerHTML = '';
      if (suggestions.length === 0) {
        dropdown.style.display = 'none';
        return;
      }
      suggestions.forEach(item => {
        const div = document.createElement('div');
        div.className = 'list-group-item list-group-item-action';
        div.textContent = item.label + ' (' + item.type.charAt(0).toUpperCase() + item.type.slice(1) + ')';
        div.dataset.type = item.type;
        div.dataset.id = item.id;
        dropdown.appendChild(div);
      });
      dropdown.style.display = 'block';
    })
    .catch(() => {
      dropdown.style.display = 'none';
      lastSuggestions = [];
    });
});

searchInput.addEventListener('keydown', function(e) {
  if (e.key === 'Enter') {
    e.preventDefault();
    const inputValue = searchInput.value.trim().toLowerCase();
    const match = lastSuggestions.find(item => item.label.toLowerCase() === inputValue);
    if (match) {
      if (match.type === 'doctor') {
        window.location.href = 'doctorInfo.php?id=' + encodeURIComponent(match.id);
      } else if (match.type === 'specialty') {
        window.location.href = 'serviceDetail.php?service=' + encodeURIComponent(match.id);
      } else if (match.type === 'condition') {
        window.location.href = 'conditionDetail.php?condition=' + encodeURIComponent(match.id);
      }
    } else {
      window.alert('No result found');
    }
  }
});

dropdown.addEventListener('mousedown', function(e) {
  if (e.target.classList.contains('list-group-item')) {
    const type = e.target.dataset.type;
    const id = e.target.dataset.id;
    if (type === 'doctor') {
      window.location.href = 'doctorInfo.php?id=' + encodeURIComponent(id);
    } else if (type === 'specialty') {
      window.location.href = 'serviceDetail.php?service=' + encodeURIComponent(id);
    } else if (type === 'condition') {
      window.location.href = 'conditionDetail.php?condition=' + encodeURIComponent(id);
    }
  }
});

document.addEventListener('click', function(e) {
  if (!dropdown.contains(e.target) && e.target !== searchInput) {
    dropdown.style.display = 'none';
  }
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
  var loginModal = document.getElementById('loginModal');
  if (loginModal) {
    loginModal.addEventListener('show.bs.modal', function () {
      // Remove the login message alert if it exists
      var alert = document.getElementById('loginMessageAlert');
      if (alert) alert.remove();
      // Reset the login form fields
      var form = loginModal.querySelector('form');
      if (form) form.reset();
    });
  }
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

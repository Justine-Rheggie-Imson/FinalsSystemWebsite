<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Doctor Specialties</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
 <style>
    .specialty-link {
      color: #333;
      text-decoration: none;
      transition: all 0.3s ease;
    }
    
    .specialty-link:hover {
      background-color: #f8f9fa;
      color: #0d6efd;
      text-decoration: none;
      transform: translateX(5px);
    }
    
    .hover-bg-light:hover {
      background-color: #f8f9fa;
    }
    .navbar-brand {
      font-weight: bold;
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
<section class="py-5 serviceSec" >
<div class="container">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Our Services</h2>
    
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
  <!-- Specialties List -->
  <div class="container py-5">
    <h2 class="mb-4">Browse Doctor Specialties</h2>
    <div class="row g-3">
      <?php
        $specialties = [
          "allergistimmunologist",
          "anesthesiologist",
          "cardiologist",
          "dermatology",
          "endocrinologist",
          "emergencymedicinephysician",
          "familymedicinephysician",
          "gastroenterologist",
          "geriatrician",
          "hematologist",
          "infectiousdiseasespecialist",
          "internistgeneralinternalmedicine",
          "nephrologistkidneyspecialist",
          "neurologist",
          "obstetriciangynecologistobgyn",
          "oncologistcancerspecialist",
          "ophthalmologisteyespecialist",
          "orthopedicsurgeon",
          "otolaryngologistentspecialist",
          "pathologist",
          "pediatrician",
          "physiatristphysicalmedicinerehabilitation",
          "plasticsurgeon",
          "psychiatrist",
          "pulmonologistlungspecialist",
          "radiologist",
          "rheumatologist",
          "sleepmedicinespecialist",
          "sportsmedicinephysician",
          "generalsurgeon",
          "urologist",
          "cardiology",
          "checkup",
          "gynecology",
          "kidneynephrologist",
          "pulmonology",
          "anesthesiology"
      ];
      
        
        sort($specialties);
        
        foreach ($specialties as $specialty) {
          // Create a URL-friendly version of the specialty name for the link
          $serviceKey = strtolower(str_replace([' ', '/', '(', ')', '&'], ['', '', '', '', 'and'], $specialty));
          $link = "serviceDetail.php?service=" . urlencode($serviceKey);
          
          echo '<div class="col-md-3 col-sm-6">';
          echo "<a href='$link' class='specialty-link d-block p-2 rounded hover-bg-light'>" . htmlspecialchars($specialty) . "</a>";
          echo '</div>';
        }
      ?>
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
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 
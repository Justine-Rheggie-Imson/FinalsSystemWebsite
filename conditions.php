<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Common Conditions</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
 <style>
    .condition-link {
      color: #333;
      text-decoration: none;
      transition: all 0.3s ease;
    }
    
    .condition-link:hover {
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
    .card-title {
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
    
  </div>
</nav>

  <!-- Conditions Card Grid -->
  <section class="py-5 bg-light">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="mb-0">Common Conditions</h2>
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

  <!-- Anchor Tag Links -->
  <div class="container py-5">
    <h4 class="mb-4">All Common Conditions</h4>
    <div class="row g-3">
      <?php
        // Define the mapping of display names to URL keys
        $conditionMapping = [
            'Arthritis' => 'arthritis',
            'Back Pain' => 'backpain',
            'Diabetes' => 'diabetes',
            'Headache' => 'headache',
            'Diarrhea' => 'diarrhea',
            'Pregnancy' => 'pregnancy',
            'Cold and Flu' => 'coldflu',
            'Allergies' => 'allergies'
        ];

        $conditions = [
          // Featured Conditions (with exact mapping)
          "Arthritis", "Back Pain", "Diabetes", "Headache", "Diarrhea",
          "Pregnancy", "Cold and Flu", "Allergies",
          
          // General Conditions
          "Kidney Stones", "COPD", "Breast Cancer", "HIV/AIDS", "Obesity", "GERD",
          "Thyroid Disorders", "Stroke", "Skin Allergies", "Hypertension", "Asthma",
          "Migraine", "Depression", "Anxiety",
          
          // Digestive System
          "Ulcerative Colitis", "Crohn's Disease", "Irritable Bowel Syndrome", "Gallstones",
          "Pancreatitis", "Celiac Disease", "Gastritis", "Hemorrhoids",
          
          // Respiratory System
          "Bronchitis", "Pneumonia", "Sinusitis", "Sleep Apnea", "Tuberculosis",
          "Pulmonary Embolism", "Lung Cancer", "Pleurisy",
          
          // Cardiovascular System
          "Heart Disease", "Arrhythmia", "Heart Failure", "Atherosclerosis",
          "Peripheral Artery Disease", "Deep Vein Thrombosis", "Varicose Veins",
          
          // Neurological Conditions
          "Epilepsy", "Multiple Sclerosis", "Parkinson's Disease", "Alzheimer's Disease",
          "Cerebral Palsy", "Bell's Palsy", "Sciatica", "Carpal Tunnel Syndrome",
          
          // Skin Conditions
          "Eczema", "Psoriasis", "Acne", "Rosacea", "Vitiligo",
          "Hives", "Shingles", "Fungal Infections",
          
          // Mental Health
          "Bipolar Disorder", "PTSD", "OCD", "Eating Disorders",
          "Schizophrenia", "ADHD", "Autism Spectrum Disorder",
          
          // Women's Health
          "Endometriosis", "PCOS", "Fibroids", "Menopause",
          "Osteoporosis", "Breast Cancer", "Cervical Cancer",
          
          // Men's Health
          "Prostate Cancer", "Erectile Dysfunction", "Benign Prostatic Hyperplasia",
          "Testicular Cancer", "Male Infertility"
        ];
        
        // Sort conditions alphabetically
        sort($conditions);
        
        foreach ($conditions as $condition) {
          // Use the mapping if it exists, otherwise create a URL-friendly version
          $conditionKey = isset($conditionMapping[$condition]) 
              ? $conditionMapping[$condition] 
              : strtolower(str_replace([' ', "'", ' and '], ['', '', ''], $condition));
          
          $link = "conditionDetail.php?condition=" . $conditionKey;
          
          // Debug output
          error_log("Condition: $condition -> Key: $conditionKey");
          
          echo '<div class="col-md-3 col-sm-6">';
          echo "<a href='$link' class='condition-link d-block p-2 rounded hover-bg-light'>$condition</a>";
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
</body>
</html>

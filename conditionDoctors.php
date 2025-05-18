<!-- condition-doctors.php -->
<?php
$condition = $_GET['condition'] ?? 'Unknown Condition';
$sampleDoctors = [
  [
    "name" => "Dr. Lara Patricia Decano",
    "specialty" => "Internal Medicine - Nephrology",
    "experience" => "10 yrs experience",
    "schedule" => "Today, 1:00 PM - 4:00 PM",
    "fee" => "₱800.00"
  ],
  [
    "name" => "Dr. Charisse Pulmano",
    "specialty" => "General Pediatrics, Pediatric Nephrology",
    "experience" => "10 yrs experience",
    "schedule" => "Today, 12:00 PM - 2:00 PM",
    "fee" => "₱650.00"
  ],
  [
    "name" => "Dr. Lydelth Pagdadagan",
    "specialty" => "Internal Medicine - Nephrology",
    "experience" => "8 yrs experience",
    "schedule" => "Today, 8:00 AM - 5:00 PM",
    "fee" => "₱500.00"
  ],
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Doctors for <?= htmlspecialchars($condition) ?></title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <style>
    .doctor-card {
      border: 1px solid #ccc;
      border-radius: 10px;
      padding: 20px;
      margin-bottom: 15px;
    }
    .btn-blue { background-color: #007bff; color: white; }
    .btn-green { background-color: #28a745; color: white; }
  </style>
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar navbar-light bg-light px-4">
  <a class="navbar-brand d-flex align-items-center" href="index.php">
    <div class="d-flex align-items-center">
      <img src="img/imgSoloLogo.png" alt="Logo" class="me-2 " style="height: 70px;">
      <img src="img/imgSoloTitle.png" alt="Title" class="pt-3" style="height: 60px;">
    </div>
    </a>
    <a class="navbar-brand" href="conditions.php">← Back to Conditions</a>
  </nav>

  <div class="container mt-4">
    <h3>Doctors Treating: <?= htmlspecialchars($condition) ?></h3>
    
    <?php foreach ($sampleDoctors as $doc): ?>
      <div class="doctor-card">
        <h5><?= $doc['name'] ?></h5>
        <p><?= $doc['specialty'] ?><br>
           <?= $doc['experience'] ?><br>
           <strong>Schedule:</strong> <?= $doc['schedule'] ?><br>
           <strong>Fee:</strong> <?= $doc['fee'] ?>
        </p>
        <div class="d-flex gap-2">
          <button class="btn btn-blue">View Profile</button>
          <button class="btn btn-green">Consult Now</button>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</body>
</html>

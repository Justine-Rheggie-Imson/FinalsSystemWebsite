<?php
include 'dbConnect.php';
$serviceKey = isset($_GET['service']) ? strtolower(trim($_GET['service'])) : 'unknown';

// Define services (expand as needed)
$services = [
  'cardiology' => [
    'title' => 'Cardiology',
    'description' => 'Cardiology is the branch of medicine that deals with disorders of the heart and blood vessels. Cardiologists diagnose and treat conditions such as coronary artery disease, heart rhythm disorders, and heart failure.'
  ],
  'checkup' => [
    'title' => 'General Checkup',
    'description' => 'A general checkup involves a comprehensive health assessment to evaluate overall well-being, detect potential health issues early, and provide preventive care recommendations.'
  ],
  'gynecology' => [
    'title' => 'Gynecology',
    'description' => 'Gynecology focuses on the health of the female reproductive system, including the uterus, ovaries, and vagina. Gynecologists diagnose and treat conditions like menstrual disorders, infertility, and menopause-related issues.'
  ],
  'kidneynephrologist' => [
    'title' => 'Kidney Nephrologist',
    'description' => 'Nephrology is the medical specialty concerned with the kidneys. Nephrologists diagnose and manage kidney diseases, including chronic kidney disease, kidney infections, and electrolyte imbalances.'
  ],
  'neurologist' => [
    'title' => 'Neurologist',
    'description' => 'Neurology deals with disorders of the nervous system. Neurologists diagnose and treat conditions such as epilepsy, multiple sclerosis, Parkinson\'s disease, and migraines.'
  ],
  'pulmonology' => [
    'title' => 'Pulmonology',
    'description' => 'Pulmonology is the medical specialty that focuses on the respiratory system. Pulmonologists treat diseases like asthma, chronic obstructive pulmonary disease (COPD), and lung infections.'
  ],
  'dermatology' => [
    'title' => 'Dermatology',
    'description' => 'Dermatology involves the study and treatment of skin, hair, and nail conditions. Dermatologists manage issues like acne, eczema, psoriasis, and skin cancers.'
  ],
  'anesthesiology' => [
    'title' => 'Anesthesiology',
    'description' => 'Anesthesiology is the branch of medicine dedicated to pain relief during and after surgical procedures. Anesthesiologists administer anesthesia and monitor patients\' vital signs during surgery.'
  ],
  'anesthesiologist' => [
    'title' => 'Anesthesiologist',
    'description' => 'An anesthesiologist is a medical doctor specializing in anesthesia and perioperative care, ensuring patient safety and comfort during surgical procedures.'
  ],
  'endocrinologist' => [
    'title' => 'Endocrinologist',
    'description' => 'Endocrinology focuses on hormone-related diseases. Endocrinologists diagnose and treat conditions like diabetes, thyroid disorders, and hormonal imbalances.'
  ],
  'emergencymedicinephysician' => [
    'title' => 'Emergency Medicine Physician',
    'description' => 'Emergency medicine physicians provide immediate care for acute illnesses and injuries, stabilizing patients and initiating treatment in emergency situations.'
  ],
  'familymedicinephysician' => [
    'title' => 'Family Medicine Physician',
    'description' => 'Family medicine physicians offer comprehensive healthcare for individuals and families across all ages, focusing on preventive care and chronic disease management.'
  ],
  'gastroenterologist' => [
    'title' => 'Gastroenterologist',
    'description' => 'Gastroenterology deals with the digestive system and its disorders. Gastroenterologists treat conditions like ulcers, irritable bowel syndrome, and liver diseases.'
  ],
  'geriatrician' => [
    'title' => 'Geriatrician',
    'description' => 'Geriatrics is the branch of medicine that focuses on health care of the elderly. Geriatricians manage multiple health issues common in older adults.'
  ],
  'hematologist' => [
    'title' => 'Hematologist',
    'description' => 'Hematology is the study of blood and blood disorders. Hematologists diagnose and treat conditions like anemia, clotting disorders, and leukemia.'
  ],
  'infectiousdiseasespecialist' => [
    'title' => 'Infectious Disease Specialist',
    'description' => 'Infectious disease specialists diagnose and treat complex infections, including those caused by bacteria, viruses, fungi, and parasites.'
  ],
  'internistgeneralinternalmedicine' => [
    'title' => 'Internist (General Internal Medicine)',
    'description' => 'Internal medicine physicians, or internists, provide comprehensive care for adults, managing a wide range of medical conditions and chronic illnesses.'
  ],
  'nephrologistkidneyspecialist' => [
    'title' => 'Nephrologist (Kidney Specialist)',
    'description' => 'Nephrologists specialize in kidney care, treating conditions like chronic kidney disease, electrolyte imbalances, and hypertension.'
  ],
  'obstetriciangynecologistobgyn' => [
    'title' => 'Obstetrician/Gynecologist (OB/GYN)',
    'description' => 'OB/GYNs specialize in women\'s reproductive health, including pregnancy, childbirth, and disorders of the reproductive system.'
  ],
  'oncologistcancerspecialist' => [
    'title' => 'Oncologist (Cancer Specialist)',
    'description' => 'Oncologists diagnose and treat cancer, developing treatment plans that may include chemotherapy, radiation, and surgery.'
  ],
  'ophthalmologisteyespecialist' => [
    'title' => 'Ophthalmologist (Eye Specialist)',
    'description' => 'Ophthalmologists are medical doctors who specialize in eye and vision care, performing eye exams, diagnosing diseases, and conducting surgeries.'
  ],
  'orthopedicsurgeon' => [
    'title' => 'Orthopedic Surgeon',
    'description' => 'Orthopedic surgeons diagnose and treat musculoskeletal system issues, including bones, joints, ligaments, tendons, and muscles.'
  ],
  'otolaryngologistentspecialist' => [
    'title' => 'Otolaryngologist (ENT Specialist)',
    'description' => 'ENT specialists diagnose and treat conditions related to the ear, nose, and throat, as well as related structures of the head and neck.'
  ],
  'pathologist' => [
    'title' => 'Pathologist',
    'description' => 'Pathologists study the causes and effects of diseases, often through laboratory analysis of body tissues and fluids.'
  ],
  'pediatrician' => [
    'title' => 'Pediatrician',
    'description' => 'Pediatricians provide medical care for infants, children, and adolescents, addressing physical, behavioral, and mental health issues.'
  ],
  'physiatristphysicalmedicinerehabilitation' => [
    'title' => 'Physiatrist (Physical Medicine & Rehabilitation)',
    'description' => 'Physiatrists specialize in physical medicine and rehabilitation, aiming to enhance and restore functional ability and quality of life to those with physical impairments.'
  ],
  'plasticsurgeon' => [
    'title' => 'Plastic Surgeon',
    'description' => 'Plastic surgeons perform reconstructive and cosmetic surgeries to repair or improve body parts for functional or aesthetic reasons.'
  ],
  'psychiatrist' => [
    'title' => 'Psychiatrist',
    'description' => 'Psychiatrists are medical doctors specializing in mental health, diagnosing and treating mental illnesses through therapy and medication.'
  ],
  'pulmonologistlungspecialist' => [
    'title' => 'Pulmonologist (Lung Specialist)',
    'description' => 'Pulmonologists diagnose and treat respiratory system diseases, including asthma, bronchitis, and chronic obstructive pulmonary disease (COPD).'
  ],
  'radiologist' => [
    'title' => 'Radiologist',
    'description' => 'Radiologists use medical imaging techniques to diagnose and sometimes treat diseases within the body.'
  ],
  'rheumatologist' => [
    'title' => 'Rheumatologist',
    'description' => 'Rheumatologists specialize in diagnosing and treating rheumatic diseases, which affect joints, muscles, and bones.'
  ],
  'sleepmedicinespecialist' => [
    'title' => 'Sleep Medicine Specialist',
    'description' => 'Sleep medicine specialists diagnose and treat sleep disorders, such as insomnia, sleep apnea, and restless legs syndrome.'
  ],
  'sportsmedicinephysician' => [
    'title' => 'Sports Medicine Physician',
    'description' => 'Sports medicine physicians focus on physical fitness and the treatment and prevention of injuries related to sports and exercise.'
  ],
  'generalsurgeon' => [
    'title' => 'General Surgeon',
    'description' => 'General surgeons perform a wide range of surgical procedures, often involving the abdominal organs, breast, skin, and soft tissues.'
  ],
  'urologist' => [
    'title' => 'Urologist',
    'description' => 'Urologists specialize in the urinary tract and male reproductive organs, treating conditions like urinary tract infections, kidney stones, and prostate issues.'
  ]
];


$info = $services[$serviceKey] ?? [
  'title' => 'Service Not Found',
  'description' => 'Sorry, no information is available for this service.'
];

// Fetch doctors with this specialty
$doctorList = [];
if ($serviceKey !== 'unknown' && isset($services[$serviceKey])) {
  // Add this before the if statement
  echo "<!-- serviceKey: '$serviceKey' -->";
  echo "<!-- isset: " . (isset($services[$serviceKey]) ? 'yes' : 'no') . " -->";
  $sql = "SELECT id, account_id, name, image, specialty FROM doctors";
  $result = $conn->query($sql);
  if ($result) {
    while ($row = $result->fetch_assoc()) {
      echo "<!-- Doctor: " . $row['name'] . " | Raw specialties: " . $row['specialty'] . " -->";
      $specialties = explode(',', $row['specialty']);
      foreach ($specialties as $spec) {
        $spec = ltrim($spec, "\xEF\xBB\xBF \t\n\r\0\x0B"); // Remove BOM and whitespace
        $normalized = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $spec));
        echo "<!-- ASCII: ";
        for ($i = 0; $i < strlen($spec); $i++) {
            echo ord($spec[$i]) . ' ';
        }
        echo "-->";
        echo "<!-- Checking specialty: '$spec' (normalized: '$normalized') vs serviceKey: '$serviceKey' -->";
        if ($normalized === $serviceKey) {
          echo "<!-- MATCH FOUND for doctor: " . $row['name'] . " -->";
          $doctorList[] = $row;
          break;
        }
      }
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?= htmlspecialchars($info['title']) ?> - HealthServe</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    .info-card {
      border-radius: 15px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s ease;
    }
    .info-card:hover {
      transform: translateY(-5px);
    }
    .specialty-card {
      border-left: 4px solid #0d6efd;
    }
    .list-item {
      margin-bottom: 0.5rem;
      padding-left: 1.5rem;
      position: relative;
    }
    .list-item:before {
      content: "•";
      position: absolute;
      left: 0;
      color: #0d6efd;
    }
  </style>
</head>
<body class="bg-light">
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
    <div class="row">
      <!-- Main Content -->
      <div class="col-lg-8">
        <div class="bg-white p-4 rounded-3 shadow-sm mb-4">
          <h1 class="mb-4"><?= htmlspecialchars($info['title']) ?></h1>
          <p class="lead mb-4"><?= htmlspecialchars($info['description']) ?></p>
        </div>
        <!-- Doctor List -->
        <div class="bg-white p-4 rounded-3 shadow-sm mb-4">
          <h3 class="h4 mb-4"><i class="bi bi-person-badge me-2"></i>Doctors Specializing in <?= htmlspecialchars($info['title']) ?></h3>
          <?php if (!empty($doctorList)): ?>
            <div class="row g-3">
              <?php foreach ($doctorList as $doc): ?>
                <div class="col-md-6">
                  <div class="d-flex align-items-center p-3 border rounded shadow-sm bg-light">
                    <img src="<?= isset($doc['image']) && $doc['image'] ? '/FinalsWeb/' . htmlspecialchars($doc['image']) : 'img/imgExmpDoc.jpg' ?>" alt="Doctor Image" class="rounded-circle me-3" style="width: 60px; height: 60px; object-fit: cover;">
                    <div>
                      <h6 class="mb-1 fw-bold"><?= htmlspecialchars($doc['name']) ?></h6>
                      <a href="doctorInfo.php?id=<?= $doc['account_id'] ?>" class="btn btn-outline-primary btn-sm mt-1">View Profile</a>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php else: ?>
            <div class="alert alert-info">No doctors found for this specialty at the moment.</div>
          <?php endif; ?>
        </div>
      </div>
      <!-- Sidebar (optional, can add more info here) -->
      <div class="col-lg-4">
        <div class="bg-white p-4 rounded-3 shadow-sm mb-4">
          <a href="specialties.php" class="btn btn-outline-secondary mb-2">
            <i class="bi bi-arrow-left me-2"></i>Back to Specialties
          </a>
        </div>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

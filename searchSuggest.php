<?php
include 'dbConnect.php';

// Validate input
$term = isset($_GET['q']) ? strtolower(trim($_GET['q'])) : '';
if (strlen($term) < 2) {
    http_response_code(400);
    die(json_encode(['error' => 'Search term too short']));
}

$suggestions = [];
$maxSuggestions = 10; // Limit results

try {
    if ($term !== '') {
        // Doctors
        $stmt = $conn->prepare("SELECT d.account_id, d.name, d.specialty FROM doctors d 
                               WHERE d.name LIKE CONCAT('%', ?, '%') LIMIT ?");
        $stmt->bind_param("si", $term, $maxSuggestions);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $suggestions[] = [
                'type' => 'doctor',
                'label' => $row['name'],
                'id' => $row['account_id']
            ];
        }
        $stmt->close();

        // Specialties 
        $stmt = $conn->prepare("SELECT DISTINCT d.specialty FROM doctors d 
                               WHERE d.specialty LIKE CONCAT('%', ?, '%') LIMIT ?");
        $stmt->bind_param("si", $term, $maxSuggestions);
        $stmt->execute();
        $result = $stmt->get_result();
        $seenSpecialties = [];
        while ($row = $result->fetch_assoc()) {
            $specialties = array_map('trim', explode(',', $row['specialty']));
            foreach ($specialties as $spec) {
                if (stripos($spec, $term) !== false && !isset($seenSpecialties[$spec])) {
                    $seenSpecialties[$spec] = true;
                    $suggestions[] = [
                        'type' => 'specialty',
                        'label' => $spec,
                        'id' => $spec
                    ];
                }
            }
        }
        $stmt->close();

        // Specialties from static data file
        if (file_exists('specialtiesData.php')) {
            include_once 'specialtiesData.php';
            if (isset($specialties) && is_array($specialties)) {
                // Merge seenSpecialties from DB and static, case-insensitive
                $seenSpecialties = array_change_key_case($seenSpecialties, CASE_LOWER);
                foreach ($specialties as $spec) {
                    $specLower = strtolower($spec);
                    if (stripos($spec, $term) !== false && !isset($seenSpecialties[$specLower])) {
                        $seenSpecialties[$specLower] = true;
                        $suggestions[] = [
                            'type' => 'specialty',
                            'label' => $spec,
                            'id' => $specLower // use the actual key, not a hash
                        ];
                        if (count($suggestions) >= $maxSuggestions) break;
                    }
                }
            }
        }

        // Conditions
        if (file_exists('conditionData.php')) {
            include_once 'conditionData.php';
            if (isset($details) && is_array($details)) {
                foreach ($details as $key => $cond) {
                    if (isset($cond['title']) && stripos($cond['title'], $term) !== false) {
                        $suggestions[] = [
                            'type' => 'condition',
                            'label' => $cond['title'],
                            'id' => $key
                        ];
                        if (count($suggestions) >= $maxSuggestions) break;
                    }
                }
            }
        }
    }

    header('Content-Type: application/json');
    echo json_encode(array_slice($suggestions, 0, $maxSuggestions));
} catch (Exception $e) {
    http_response_code(500);
    die(json_encode(['error' => 'Server error']));
}
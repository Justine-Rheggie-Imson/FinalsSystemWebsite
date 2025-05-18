<?php
session_start();
require_once '../../dbConnect.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if user is logged in and is a doctor
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'doctor') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

// Verify CSRF token
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    echo json_encode(['success' => false, 'message' => 'Invalid security token']);
    exit;
}

$doctorId = $_SESSION['user_id'];

// Define allowed fields
$allowedFields = [
    'name', 'specialty', 'subspecialty', 'experience', 'fee', 
    'affiliation', 'education', 'certifications', 'bio', 
    'in_person', 'online'
];

try {
    $updates = [];
    $params = [];
    $types = '';

    // Handle file upload separately
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['image'];
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $maxSize = 5 * 1024 * 1024; // 5MB

        if (!in_array($file['type'], $allowedTypes)) {
            throw new Exception('Invalid file type. Only JPG, PNG, and GIF are allowed.');
        }

        if ($file['size'] > $maxSize) {
            throw new Exception('File size too large. Maximum size is 5MB.');
        }

        $uploadDir = '../../uploads/doctors/';
        if (!file_exists($uploadDir)) {
            if (!mkdir($uploadDir, 0777, true)) {
                throw new Exception('Failed to create upload directory.');
            }
        }

        $fileName = uniqid('doctor_') . '_' . basename($file['name']);
        $targetPath = $uploadDir . $fileName;

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            $updates[] = "image = ?";
            $params[] = 'uploads/doctors/' . $fileName;
            $types .= 's';
        } else {
            throw new Exception('Failed to upload file. Error: ' . error_get_last()['message']);
        }
    }

    // Process other fields
    foreach ($_POST as $field => $value) {
        if (in_array($field, $allowedFields)) {
            // Handle checkbox fields
            if ($field === 'in_person' || $field === 'online') {
                $value = isset($_POST[$field]) ? '1' : '0';
            }
            
            if ($value !== '') {
                $updates[] = "$field = ?";
                $params[] = $value;
                $types .= 's';
            }
        }
    }

    if (empty($updates)) {
        echo json_encode(['success' => false, 'message' => 'No fields to update']);
        exit;
    }

    // Prepare the update query
    $sql = "UPDATE doctors SET " . implode(', ', $updates) . " WHERE account_id = ?";
    
    // Log the SQL query for debugging
    error_log("SQL Query: " . $sql);
    error_log("Parameters: " . print_r($params, true));
    error_log("Types: " . $types);

    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        throw new Exception("Query preparation failed: " . $conn->error);
    }

    // Add doctor ID to parameters
    $params[] = $doctorId;
    $types .= 'i';

    // Bind parameters
    if (!$stmt->bind_param($types, ...$params)) {
        throw new Exception("Parameter binding failed: " . $stmt->error);
    }
    
    if (!$stmt->execute()) {
        throw new Exception("Query execution failed: " . $stmt->error);
    }

    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true, 'message' => 'Profile updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'No changes were made']);
    }

} catch (Exception $e) {
    error_log("Profile update error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$stmt->close();
$conn->close();
?> 
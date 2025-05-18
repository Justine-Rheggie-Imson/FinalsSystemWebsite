<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 0);
header('Content-Type: application/json');

// Check if user is logged in and is a client
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'client') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

// Initialize response array
$response = ['success' => false, 'message' => ''];

try {
    // Include database connection
    require_once '../../dbConnect.php';
    
    if (!$conn) {
        throw new Exception('Database connection failed');
    }

    // Check if file and doctor_id are set
    if (!isset($_FILES['file']) || !isset($_POST['doctor_id'])) {
        throw new Exception('Missing required fields');
    }

    $file = $_FILES['file'];
    $doctorId = $_POST['doctor_id'];
    $clientId = $_SESSION['user_id'];

    // Debug information
    error_log("Upload attempt - File: " . print_r($file, true));
    error_log("Doctor ID: $doctorId, Client ID: $clientId");

    // Validate file
    $maxSize = 5 * 1024 * 1024; // 5MB
    if ($file['size'] > $maxSize) {
        throw new Exception('File size exceeds 5MB limit');
    }

    // Validate file type
    $allowedTypes = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'image/jpeg',
        'image/png'
    ];
    if (!in_array($file['type'], $allowedTypes)) {
        throw new Exception('Invalid file type. Allowed types: PDF, DOC, DOCX, JPG, PNG');
    }

    // Create upload directory if it doesn't exist
    $uploadDir = '../../uploads/shared_files/';
    if (!file_exists($uploadDir)) {
        if (!mkdir($uploadDir, 0777, true)) {
            throw new Exception('Failed to create upload directory: ' . error_get_last()['message']);
        }
    }

    // Check if directory is writable
    if (!is_writable($uploadDir)) {
        throw new Exception('Upload directory is not writable');
    }

    // Generate unique filename
    $fileName = uniqid('file_') . '_' . basename($file['name']);
    $targetPath = $uploadDir . $fileName;

    // Move uploaded file
    if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
        $uploadError = error_get_last();
        throw new Exception('Failed to move uploaded file: ' . ($uploadError ? $uploadError['message'] : 'Unknown error'));
    }

    // Save file record to database
    $stmt = $conn->prepare("
        INSERT INTO shared_files (sender_id, recipient_id, filename, filepath, upload_date)
        VALUES (?, ?, ?, ?, NOW())
    ");

    if (!$stmt) {
        // Log the actual SQL error
        error_log("SQL Error: " . $conn->error);
        throw new Exception('Database prepare failed: ' . $conn->error);
    }

    $filePath = 'uploads/shared_files/' . $fileName;
    if (!$stmt->bind_param("iiss", $clientId, $doctorId, $file['name'], $filePath)) {
        throw new Exception('Parameter binding failed: ' . $stmt->error);
    }

    if (!$stmt->execute()) {
        // Delete uploaded file if database insert fails
        unlink($targetPath);
        throw new Exception('Failed to save file record: ' . $stmt->error);
    }

    // Success response
    $response = [
        'success' => true,
        'message' => 'File uploaded successfully',
        'file' => [
            'id' => $stmt->insert_id,
            'filename' => $file['name'],
            'file_path' => '/FinalsWeb/' . $filePath,
            'upload_date' => date('Y-m-d H:i:s'),
            'sender_name' => 'You'
        ]
    ];

} catch (Exception $e) {
    error_log("File upload error: " . $e->getMessage());
    $response = [
        'success' => false,
        'message' => $e->getMessage()
    ];
} finally {
    // Close database connections if they were opened
    if (isset($stmt) && $stmt) {
        $stmt->close();
    }
    if (isset($conn) && $conn) {
        $conn->close();
    }
}

// Ensure we have a valid response
if (!isset($response['success'])) {
    $response = [
        'success' => false,
        'message' => 'Unknown error occurred'
    ];
}

// Output JSON response
echo json_encode($response);
exit;
?> 
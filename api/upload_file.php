<?php
session_start();
require_once '../config/database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Check if file was uploaded
if (!isset($_FILES['file']) || !isset($_POST['recipient_id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'File and recipient ID are required']);
    exit;
}

$file = $_FILES['file'];
$recipientId = $_POST['recipient_id'];

// Validate file size (5MB limit)
if ($file['size'] > 5 * 1024 * 1024) {
    http_response_code(400);
    echo json_encode(['error' => 'File size exceeds 5MB limit']);
    exit;
}

// Validate file type
$allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'image/jpeg', 'image/png'];
if (!in_array($file['type'], $allowedTypes)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid file type. Allowed types: PDF, DOC, DOCX, JPG, PNG']);
    exit;
}

try {
    // Create uploads directory if it doesn't exist
    $uploadDir = '../uploads/';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Generate unique filename
    $filename = uniqid() . '_' . basename($file['name']);
    $filepath = $uploadDir . $filename;

    // Move uploaded file
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        // Save file information to database
        $query = "INSERT INTO shared_files (sender_id, recipient_id, filename, filepath, upload_date) 
                  VALUES (?, ?, ?, ?, NOW())";
        
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iiss", $_SESSION['user_id'], $recipientId, $file['name'], $filepath);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'File uploaded successfully']);
        } else {
            unlink($filepath); // Delete file if database insert fails
            throw new Exception('Failed to save file information');
        }
    } else {
        throw new Exception('Failed to move uploaded file');
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to upload file: ' . $e->getMessage()]);
}
?> 
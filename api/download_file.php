<?php
session_start();
require_once '../config/database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$fileId = $_GET['file_id'] ?? null;

if (!$fileId) {
    http_response_code(400);
    echo json_encode(['error' => 'File ID is required']);
    exit;
}

try {
    // Get file information
    $query = "SELECT * FROM shared_files 
              WHERE id = ? 
              AND (sender_id = ? OR recipient_id = ?)";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iii", $fileId, $_SESSION['user_id'], $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($file = $result->fetch_assoc()) {
        // Check if file exists
        if (file_exists($file['filepath'])) {
            // Set headers for file download
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $file['filename'] . '"');
            header('Content-Length: ' . filesize($file['filepath']));
            header('Cache-Control: no-cache, must-revalidate');
            header('Pragma: no-cache');
            header('Expires: 0');
            
            // Output file
            readfile($file['filepath']);
            exit;
        } else {
            throw new Exception('File not found on server');
        }
    } else {
        throw new Exception('File not found in database');
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to download file: ' . $e->getMessage()]);
}
?> 
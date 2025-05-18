<?php
session_start();
require_once '../config/database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get JSON data
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['recipient_id']) || !isset($data['message'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Recipient ID and message are required']);
    exit;
}

try {
    $query = "INSERT INTO messages (sender_id, recipient_id, message, timestamp, is_read) 
              VALUES (?, ?, ?, NOW(), 0)";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iis", $_SESSION['user_id'], $data['recipient_id'], $data['message']);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        throw new Exception('Failed to send message');
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error']);
}
?> 
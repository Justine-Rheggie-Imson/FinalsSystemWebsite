<?php
session_start();
require_once '../config/database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$userId = $_GET['user_id'] ?? null;

if (!$userId) {
    http_response_code(400);
    echo json_encode(['error' => 'User ID is required']);
    exit;
}

try {
    // Get files shared between the two users
    $query = "SELECT f.*, 
              u.name as sender_name,
              DATE_FORMAT(f.upload_date, '%M %d, %Y %h:%i %p') as formatted_date
              FROM shared_files f
              JOIN users u ON f.sender_id = u.id
              WHERE (f.sender_id = ? AND f.recipient_id = ?) 
              OR (f.sender_id = ? AND f.recipient_id = ?)
              ORDER BY f.upload_date DESC";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iiii", $_SESSION['user_id'], $userId, $userId, $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $files = [];
    while ($row = $result->fetch_assoc()) {
        $files[] = [
            'id' => $row['id'],
            'filename' => $row['filename'],
            'sender_name' => $row['sender_name'],
            'upload_date' => $row['formatted_date']
        ];
    }
    
    echo json_encode($files);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error']);
}
?> 
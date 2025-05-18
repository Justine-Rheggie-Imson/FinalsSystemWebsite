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
    // Get messages between the two users
    $query = "SELECT m.*, 
              CASE WHEN m.sender_id = ? THEN 1 ELSE 0 END as is_sender,
              DATE_FORMAT(m.timestamp, '%M %d, %Y %h:%i %p') as formatted_time
              FROM messages m 
              WHERE (m.sender_id = ? AND m.recipient_id = ?) 
              OR (m.sender_id = ? AND m.recipient_id = ?)
              ORDER BY m.timestamp ASC";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iiiii", $_SESSION['user_id'], $_SESSION['user_id'], $userId, $userId, $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $messages = [];
    while ($row = $result->fetch_assoc()) {
        $messages[] = [
            'message' => $row['message'],
            'timestamp' => $row['formatted_time'],
            'is_sender' => (bool)$row['is_sender']
        ];
    }
    
    // Mark unread messages as read
    $updateQuery = "UPDATE messages 
                   SET is_read = 1 
                   WHERE sender_id = ? 
                   AND recipient_id = ? 
                   AND is_read = 0";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param("ii", $userId, $_SESSION['user_id']);
    $updateStmt->execute();
    
    echo json_encode($messages);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error']);
}
?> 
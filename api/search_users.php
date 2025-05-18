<?php
session_start();
require_once '../config/database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$searchTerm = $_GET['term'] ?? '';
$userRole = $_SESSION['role'];

// Determine which role to search for based on current user's role
$searchRole = ($userRole === 'client') ? 'doctor' : 'client';

try {
    $query = "SELECT u.id, u.name, 
              (SELECT COUNT(*) FROM messages m 
               WHERE m.sender_id = u.id 
               AND m.recipient_id = ? 
               AND m.is_read = 0) as unread_count,
              (SELECT message FROM messages m 
               WHERE (m.sender_id = u.id AND m.recipient_id = ?) 
               OR (m.sender_id = ? AND m.recipient_id = u.id)
               ORDER BY m.timestamp DESC LIMIT 1) as last_message
              FROM users u 
              WHERE u.role = ? 
              AND u.name LIKE ? 
              ORDER BY u.name";
    
    $stmt = $conn->prepare($query);
    $searchPattern = "%$searchTerm%";
    $stmt->bind_param("iiiss", $_SESSION['user_id'], $_SESSION['user_id'], $_SESSION['user_id'], $searchRole, $searchPattern);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $users = [];
    while ($row = $result->fetch_assoc()) {
        $users[] = [
            'id' => $row['id'],
            'name' => $row['name'],
            'unread_count' => (int)$row['unread_count'],
            'last_message' => $row['last_message']
        ];
    }
    
    echo json_encode($users);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error']);
}
?> 
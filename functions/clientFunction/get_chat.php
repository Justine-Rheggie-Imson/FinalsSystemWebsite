<?php
session_start();
header('Content-Type: application/json');

// Check if user is logged in and is a client
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'client') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

// Get doctor_id from query string
$doctorId = isset($_GET['doctor_id']) ? (int)$_GET['doctor_id'] : 0;
if (!$doctorId) {
    echo json_encode(['success' => false, 'message' => 'Invalid doctor ID']);
    exit;
}

include '../../dbConnect.php';

try {
    // Get messages between client and doctor
    $stmt = $conn->prepare("
        SELECT 
            m.*,
            CASE 
                WHEN m.sender_id = ? THEN 'sent'
                ELSE 'received'
            END as message_type
        FROM chat_messages m
        WHERE (m.sender_id = ? AND m.receiver_id = ?)
           OR (m.sender_id = ? AND m.receiver_id = ?)
        ORDER BY m.created_at ASC
    ");
    
    $clientId = $_SESSION['user_id'];
    $stmt->bind_param("iiiii", $clientId, $clientId, $doctorId, $doctorId, $clientId);
    
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $messages = [];
        
        while ($row = $result->fetch_assoc()) {
            $messages[] = $row;
        }
        
        // Mark unread messages as read
        $updateStmt = $conn->prepare("
            UPDATE chat_messages 
            SET is_read = TRUE 
            WHERE sender_id = ? AND receiver_id = ? AND is_read = FALSE
        ");
        $updateStmt->bind_param("ii", $doctorId, $clientId);
        $updateStmt->execute();
        $updateStmt->close();
        
        echo json_encode(['success' => true, 'messages' => $messages]);
    } else {
        throw new Exception($stmt->error);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error retrieving messages: ' . $e->getMessage()]);
}

$stmt->close();
$conn->close();
?> 
<?php
session_start();
header('Content-Type: application/json');

// Check if user is logged in and is a doctor
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'doctor') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

include '../../dbConnect.php';

try {
    // Get patients with unread message counts
    $stmt = $conn->prepare("
        SELECT 
            a.id,
            a.fullname as name,
            COUNT(CASE WHEN m.is_read = FALSE AND m.receiver_id = ? THEN 1 END) as unread_count
        FROM accounts a
        LEFT JOIN chat_messages m ON (m.sender_id = a.id AND m.receiver_id = ?) OR (m.sender_id = ? AND m.receiver_id = a.id)
        WHERE a.role = 'patient'
        GROUP BY a.id, a.fullname
        ORDER BY unread_count DESC, a.fullname ASC
    ");
    
    $doctorId = $_SESSION['user_id'];
    $stmt->bind_param("iii", $doctorId, $doctorId, $doctorId);
    
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $patients = [];
        
        while ($row = $result->fetch_assoc()) {
            $patients[] = $row;
        }
        
        echo json_encode(['success' => true, 'patients' => $patients]);
    } else {
        throw new Exception($stmt->error);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error retrieving patients: ' . $e->getMessage()]);
}

$stmt->close();
$conn->close();
?> 
<?php
session_start();
header('Content-Type: application/json');

// Check if user is logged in and is a client
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'client') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

include '../../dbConnect.php';

try {
    // Get doctors with unread message counts
    $stmt = $conn->prepare("
        SELECT 
            a.id,
            d.name,
            d.image,
            COUNT(CASE WHEN m.is_read = FALSE AND m.receiver_id = ? THEN 1 END) as unread_count
        FROM accounts a
        JOIN doctors d ON a.id = d.account_id
        LEFT JOIN chat_messages m ON (m.sender_id = a.id AND m.receiver_id = ?) OR (m.sender_id = ? AND m.receiver_id = a.id)
        WHERE a.role = 'doctor'
        GROUP BY a.id, d.name, d.image
        ORDER BY unread_count DESC, d.name ASC
    ");
    
    $clientId = $_SESSION['user_id'];
    $stmt->bind_param("iii", $clientId, $clientId, $clientId);
    
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $doctors = [];
        
        while ($row = $result->fetch_assoc()) {
            // Ensure image path is properly formatted
            if ($row['image']) {
                $row['image'] = '/FinalsWeb/' . $row['image'];
            }
            $doctors[] = $row;
        }
        
        echo json_encode(['success' => true, 'doctors' => $doctors]);
    } else {
        throw new Exception($stmt->error);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error retrieving doctors: ' . $e->getMessage()]);
}

$stmt->close();
$conn->close();
?> 
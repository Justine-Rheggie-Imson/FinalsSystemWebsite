<?php
session_start();
header('Content-Type: application/json');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Log function for debugging
function logError($message) {
    error_log(date('[Y-m-d H:i:s] ') . $message . "\n", 3, __DIR__ . '/chat_errors.log');
}

// Check if user is logged in and is a client
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'client') {
    logError('Unauthorized access attempt - User not logged in or not a client');
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

// Get POST data
$rawData = file_get_contents('php://input');
logError('Received raw data: ' . $rawData);

$data = json_decode($rawData, true);

// Validate required fields
if (!isset($data['receiver_id']) || !isset($data['message']) || !isset($data['csrf_token'])) {
    logError('Missing required fields in request: ' . print_r($data, true));
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

// Validate CSRF token
if (!isset($_SESSION['csrf_token']) || $data['csrf_token'] !== $_SESSION['csrf_token']) {
    logError('CSRF token validation failed. Session token: ' . ($_SESSION['csrf_token'] ?? 'not set') . ', Received token: ' . $data['csrf_token']);
    echo json_encode(['success' => false, 'message' => 'Invalid CSRF token']);
    exit;
}

include '../../dbConnect.php';

try {
    // First, check if the table exists
    $tableCheck = $conn->query("SHOW TABLES LIKE 'chat_messages'");
    if ($tableCheck->num_rows == 0) {
        // Table doesn't exist, create it
        $createTable = "CREATE TABLE IF NOT EXISTS chat_messages (
            id INT AUTO_INCREMENT PRIMARY KEY,
            sender_id INT NOT NULL,
            receiver_id INT NOT NULL,
            message TEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            is_read BOOLEAN DEFAULT FALSE
        )";
        
        if (!$conn->query($createTable)) {
            throw new Exception('Failed to create chat_messages table: ' . $conn->error);
        }
        logError('Created chat_messages table');
    }

    // Log the attempt to insert message
    logError('Attempting to insert message - Sender: ' . $_SESSION['user_id'] . ', Receiver: ' . $data['receiver_id'] . ', Message: ' . $data['message']);

    // Insert message
    $stmt = $conn->prepare("
        INSERT INTO chat_messages (sender_id, receiver_id, message, created_at)
        VALUES (?, ?, ?, NOW())
    ");
    
    if (!$stmt) {
        throw new Exception('Failed to prepare statement: ' . $conn->error);
    }
    
    $clientId = $_SESSION['user_id'];
    $stmt->bind_param("iis", $clientId, $data['receiver_id'], $data['message']);
    
    if ($stmt->execute()) {
        logError('Message successfully inserted with ID: ' . $stmt->insert_id);
        echo json_encode([
            'success' => true,
            'message_id' => $stmt->insert_id,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    } else {
        throw new Exception('Failed to execute statement: ' . $stmt->error);
    }
} catch (Exception $e) {
    logError('Error in send_message.php: ' . $e->getMessage());
    echo json_encode([
        'success' => false, 
        'message' => 'Error sending message: ' . $e->getMessage()
    ]);
}

if (isset($stmt)) {
    $stmt->close();
}
if (isset($conn)) {
    $conn->close();
}
?> 
<?php
// Prevent any output before JSON response
ob_start();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', '../../logs/php_errors.log');

// Function to handle errors and return JSON response
function sendJsonResponse($success, $message, $data = null) {
    ob_end_clean();
    $response = [
        'success' => $success,
        'message' => $message
    ];
    if ($data !== null) {
        $response['files'] = $data;
    }
    echo json_encode($response);
    exit;
}

session_start();
header('Content-Type: application/json');

// Check if user is logged in and is a client
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'client') {
    sendJsonResponse(false, 'Unauthorized access');
}

try {
    // Check if dbConnect.php exists
    if (!file_exists('../../dbConnect.php')) {
        throw new Exception('Database connection file not found');
    }

    require_once '../../dbConnect.php';

    // Check database connection
    if (!isset($conn) || !($conn instanceof mysqli)) {
        throw new Exception('Database connection failed');
    }

    $clientId = $_SESSION['user_id'];
    $doctorId = isset($_GET['doctor_id']) ? intval($_GET['doctor_id']) : null;

    if (!$doctorId) {
        throw new Exception('Doctor ID is required');
    }

    // Debug: Check if the shared_files table exists
    $tableCheck = $conn->query("SHOW TABLES LIKE 'shared_files'");
    if ($tableCheck === false) {
        throw new Exception('Failed to check shared_files table: ' . $conn->error);
    }
    if ($tableCheck->num_rows === 0) {
        throw new Exception('Shared files table does not exist');
    }

    // Get shared files between client and doctor
    $query = "
        SELECT 
            sf.id,
            sf.filename,
            sf.filepath,
            sf.upload_date,
            CASE 
                WHEN sf.sender_id = ? THEN 'You'
                ELSE sender.fullname
            END as sender_name
        FROM shared_files sf
        JOIN accounts sender ON sf.sender_id = sender.id
        WHERE (sf.sender_id = ? AND sf.recipient_id = ?)
           OR (sf.sender_id = ? AND sf.recipient_id = ?)
        ORDER BY sf.upload_date DESC";

    $stmt = $conn->prepare($query);
    if (!$stmt) {
        throw new Exception('Failed to prepare statement: ' . $conn->error);
    }

    $stmt->bind_param("iiiii", $clientId, $clientId, $doctorId, $doctorId, $clientId);
    
    if (!$stmt->execute()) {
        throw new Exception('Failed to execute query: ' . $stmt->error);
    }

    $result = $stmt->get_result();
    if ($result === false) {
        throw new Exception('Failed to get result: ' . $stmt->error);
    }

    $files = [];
    while ($row = $result->fetch_assoc()) {
        $row['filepath'] = '/FinalsWeb/' . $row['filepath'];
        $files[] = $row;
    }
    
    sendJsonResponse(true, 'Files retrieved successfully', $files);

} catch (Exception $e) {
    error_log('Error in get_shared_files.php: ' . $e->getMessage());
    sendJsonResponse(false, 'Error retrieving files: ' . $e->getMessage());
} finally {
    if (isset($stmt)) {
        $stmt->close();
    }
    if (isset($conn)) {
        $conn->close();
    }
}
?> 
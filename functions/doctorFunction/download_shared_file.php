<?php
session_start();

// Enable error logging
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', '../../logs/php_errors.log');

// Debug logging
error_log("Download request received. Session user_id: " . (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'not set'));
error_log("GET parameters: " . print_r($_GET, true));

if (!isset($_SESSION['user_id'])) {
    error_log("Download failed: No user_id in session");
    http_response_code(403);
    exit('Unauthorized');
}

if (!isset($_GET['id'])) {
    error_log("Download failed: No file ID provided");
    http_response_code(400);
    exit('No file specified.');
}

require_once '../../dbConnect.php';
$fileId = intval($_GET['id']);
$userId = $_SESSION['user_id'];

error_log("Attempting to download file ID: $fileId for user ID: $userId");

// Check if user is sender or recipient
$stmt = $conn->prepare("SELECT filename, filepath FROM shared_files WHERE id = ? AND (sender_id = ? OR recipient_id = ?)");
$stmt->bind_param('iii', $fileId, $userId, $userId);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows === 0) {
    error_log("Download failed: User not authorized to access file ID: $fileId");
    http_response_code(403);
    exit('You do not have permission to download this file.');
}
$stmt->bind_result($filename, $filepath);
$stmt->fetch();
$stmt->close();
$conn->close();

error_log("File found: $filename at path: $filepath");

$fullPath = realpath('../../' . $filepath);
if (!$fullPath || !file_exists($fullPath)) {
    error_log("Download failed: File not found at path: $fullPath");
    http_response_code(404);
    exit('File not found.');
}

error_log("Sending file: $filename from path: $fullPath");

// If you see this, the file is accessible:
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . basename($filename) . '"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($fullPath));

error_log("Starting file download");
readfile($fullPath);
error_log("File download completed");
exit; 
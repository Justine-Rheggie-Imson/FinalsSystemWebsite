<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    exit('Unauthorized');
}

if (!isset($_GET['id'])) {
    http_response_code(400);
    exit('No file specified.');
}

require_once '../../dbConnect.php';
$fileId = intval($_GET['id']);
$userId = $_SESSION['user_id'];

// Check if user is sender or recipient
$stmt = $conn->prepare("SELECT filename, filepath FROM shared_files WHERE id = ? AND (sender_id = ? OR recipient_id = ?)");
$stmt->bind_param('iii', $fileId, $userId, $userId);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows === 0) {
    http_response_code(403);
    exit('You do not have permission to download this file.');
}
$stmt->bind_result($filename, $filepath);
$stmt->fetch();
$stmt->close();
$conn->close();

$fullPath = realpath('../../' . $filepath);
if (!$fullPath || !file_exists($fullPath)) {
    http_response_code(404);
    exit('File not found.');
}

header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . basename($filename) . '"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($fullPath));
readfile($fullPath);
exit; 
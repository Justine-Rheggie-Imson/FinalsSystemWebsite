<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'doctor') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

require_once '../../dbConnect.php';

$doctorId = $_SESSION['user_id'];
$patientId = isset($_GET['patient_id']) ? intval($_GET['patient_id']) : 0;

if (!$patientId) {
    echo json_encode(['success' => false, 'message' => 'No patient selected.']);
    exit;
}

$sql = "
    SELECT f.*, a.fullname AS sender_name
    FROM shared_files f
    JOIN accounts a ON f.sender_id = a.id
    WHERE (f.sender_id = ? AND f.recipient_id = ?)
       OR (f.sender_id = ? AND f.recipient_id = ?)
    ORDER BY f.upload_date DESC
";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
    exit;
}
$stmt->bind_param('iiii', $doctorId, $patientId, $patientId, $doctorId);
$stmt->execute();
$result = $stmt->get_result();
$files = [];
while ($row = $result->fetch_assoc()) {
    $files[] = [
        'id' => $row['id'],
        'filename' => $row['filename'],
        'filepath' => '/FinalsWeb/' . $row['filepath'],
        'upload_date' => $row['upload_date'],
        'sender_name' => $row['sender_name'],
    ];
}
$stmt->close();
$conn->close();
echo json_encode(['success' => true, 'files' => $files]); 
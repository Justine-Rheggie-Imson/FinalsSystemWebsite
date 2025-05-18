<?php
require_once '../config/database.php';

$date = $_GET['date'] ?? null;
$time = $_GET['time'] ?? null;

if (!$date || !$time) {
    echo json_encode([]);
    exit;
}

$sql = "SELECT d.account_id, d.name, d.specialty
        FROM doctors d
        JOIN accounts a ON d.account_id = a.id
        WHERE d.account_id NOT IN (
            SELECT doctor_id FROM doctor_unavailability WHERE date = ? AND time_slot = ?
        )";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ss', $date, $time);
$stmt->execute();
$result = $stmt->get_result();

$doctors = [];
while ($row = $result->fetch_assoc()) {
    $doctors[] = $row;
}
echo json_encode($doctors); 
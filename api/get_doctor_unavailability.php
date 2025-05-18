<?php
require_once '../config/database.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode([]);
    exit;
}
$doctor_id = $_SESSION['user_id'];
$from_date = $_GET['from_date'] ?? null;
$to_date = $_GET['to_date'] ?? null;

if (!$from_date || !$to_date) {
    echo json_encode([]);
    exit;
}
$stmt = $conn->prepare("SELECT date, time_slot FROM doctor_unavailability WHERE doctor_id = ? AND date >= ? AND date <= ?");
$stmt->bind_param("iss", $doctor_id, $from_date, $to_date);
$stmt->execute();
$result = $stmt->get_result();
$slots = [];
while ($row = $result->fetch_assoc()) {
    $date = $row['date'];
    if (!isset($slots[$date])) $slots[$date] = [];
    $slots[$date][] = $row['time_slot'];
}
echo json_encode($slots); 
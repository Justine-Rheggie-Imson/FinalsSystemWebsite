<?php
require_once '../../config/database.php';
session_start();

function dateRange($start, $end) {
    $dates = [];
    $current = strtotime($start);
    $end = strtotime($end);
    while ($current <= $end) {
        $dates[] = date('Y-m-d', $current);
        $current = strtotime('+1 day', $current);
    }
    return $dates;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $doctor_id = $_SESSION['user_id'];
    $from_date = $_POST['from_date'] ?? null;
    $to_date = $_POST['to_date'] ?? null;
    $time_slots = $_POST['time_slots'] ?? [];

    if (!$from_date || !$to_date || empty($time_slots)) {
        echo json_encode(['success' => false, 'message' => 'Please select a date range and at least one time slot.']);
        exit;
    }

    $dates = dateRange($from_date, $to_date);

    // Remove previous unavailability for this doctor in the range
    $stmt = $conn->prepare("DELETE FROM doctor_unavailability WHERE doctor_id = ? AND date >= ? AND date <= ?");
    $stmt->bind_param("iss", $doctor_id, $from_date, $to_date);
    $stmt->execute();

    // Insert new unavailable slots for each date in the range
    $stmt = $conn->prepare("INSERT INTO doctor_unavailability (doctor_id, date, time_slot) VALUES (?, ?, ?)");
    foreach ($dates as $date) {
        foreach ($time_slots as $slot) {
            $stmt->bind_param("iss", $doctor_id, $date, $slot);
            $stmt->execute();
        }
    }
    echo json_encode(['success' => true]);
    exit;
}
echo json_encode(['success' => false, 'message' => 'Invalid request']); 
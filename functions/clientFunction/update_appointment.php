<?php
session_start();

function sendResponse($success, $message, $data = null) {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data
    ]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['user_id']) || $_SESSION['role'] !== 'client') {
    sendResponse(false, "Unauthorized access.");
}

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!$data) {
    sendResponse(false, "Invalid request data.");
}

if (!isset($_SESSION['csrf_token']) || !isset($data['csrf_token']) || $data['csrf_token'] !== $_SESSION['csrf_token']) {
    sendResponse(false, "Invalid CSRF token.");
}

if (!isset($data['appointment_id'])) {
    sendResponse(false, "Missing required fields.");
}

$appointmentId = $data['appointment_id'];
$status = $data['status'] ?? null;
$clientId = $_SESSION['user_id'];

include '../../dbConnect.php';

try {
    // First verify the appointment belongs to this client
    $checkStmt = $conn->prepare("SELECT * FROM appointments WHERE id = ? AND client_id = ?");
    if (!$checkStmt) {
        sendResponse(false, "Database error: " . $conn->error);
    }
    $checkStmt->bind_param("ii", $appointmentId, $clientId);
    $checkStmt->execute();
    $result = $checkStmt->get_result();
    if ($result->num_rows === 0) {
        sendResponse(false, "Appointment not found or unauthorized.");
    }
    $appointment = $result->fetch_assoc();
    $checkStmt->close();

    if ($status === 'cancelled') {
        $stmt = $conn->prepare("UPDATE appointments SET status = 'cancelled' WHERE id = ? AND client_id = ?");
        $stmt->bind_param("ii", $appointmentId, $clientId);
        $stmt->execute();
        sendResponse(true, "Appointment cancelled.");
    }

    // Propose reschedule
    if (isset($data['new_date']) && isset($data['new_time'])) {
        $new_date = $data['new_date'];
        $new_time = $data['new_time'];
        $stmt = $conn->prepare("UPDATE appointments SET reschedule_requested_by = 'client', new_date = ?, new_time = ?, reschedule_status = 'pending' WHERE id = ? AND client_id = ?");
        $stmt->bind_param("ssii", $new_date, $new_time, $appointmentId, $clientId);
        $stmt->execute();
        sendResponse(true, "Reschedule requested.");
    }

    // Accept/decline reschedule
    if (isset($data['reschedule_action'])) {
        if ($data['reschedule_action'] === 'accept') {
            // Update main date/time, clear reschedule fields
            $stmt = $conn->prepare("UPDATE appointments SET date = new_date, time = new_time, reschedule_requested_by = NULL, new_date = NULL, new_time = NULL, reschedule_status = 'accepted', status = 'confirmed' WHERE id = ? AND client_id = ?");
            $stmt->bind_param("ii", $appointmentId, $clientId);
            $stmt->execute();
            sendResponse(true, "Reschedule accepted.");
        } elseif ($data['reschedule_action'] === 'decline') {
            // Clear reschedule fields
            $stmt = $conn->prepare("UPDATE appointments SET reschedule_requested_by = NULL, new_date = NULL, new_time = NULL, reschedule_status = 'declined' WHERE id = ? AND client_id = ?");
            $stmt->bind_param("ii", $appointmentId, $clientId);
            $stmt->execute();
            sendResponse(true, "Reschedule declined.");
        }
    }

    // Default: status update (pending/confirmed/completed)
    if ($status) {
        $validStatuses = ['pending', 'confirmed', 'cancelled', 'completed'];
        if (!in_array($status, $validStatuses)) {
            sendResponse(false, "Invalid status.");
        }
        $stmt = $conn->prepare("UPDATE appointments SET status = ? WHERE id = ? AND client_id = ?");
        $stmt->bind_param("sii", $status, $appointmentId, $clientId);
        $stmt->execute();
        sendResponse(true, "Appointment status updated successfully.", [
            'appointment_id' => $appointmentId,
            'status' => $status
        ]);
    }

    sendResponse(false, "No valid action performed.");

} catch (Exception $e) {
    sendResponse(false, "An error occurred: " . $e->getMessage());
} finally {
    if (isset($stmt)) {
        $stmt->close();
    }
    if (isset($checkStmt)) {
        $checkStmt->close();
    }
    $conn->close();
} 
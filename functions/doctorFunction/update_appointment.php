<?php
session_start();

// Function to send JSON response
function sendResponse($success, $message, $data = null) {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data
    ]);
    exit;
}

// Check authentication
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['user_id']) || $_SESSION['role'] !== 'doctor') {
    sendResponse(false, "Unauthorized access.");
}

// Get JSON data
$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!$data) {
    sendResponse(false, "Invalid request data.");
}

// Validate CSRF token
if (!isset($_SESSION['csrf_token']) || !isset($data['csrf_token']) || $data['csrf_token'] !== $_SESSION['csrf_token']) {
    sendResponse(false, "Invalid CSRF token.");
}

// Validate required fields
if (!isset($data['appointment_id'])) {
    sendResponse(false, "Missing required fields.");
}

$appointmentId = $data['appointment_id'];
$status = $data['status'] ?? null;
$doctorId = $_SESSION['user_id'];

// Corrected include path (assuming dbConnect.php is two levels up)
// If dbConnect.php is in /FinalsWeb/ and this script is in /FinalsWeb/functions/doctorFunction/
include '../../dbConnect.php'; 

// Check if $conn was initialized by dbConnect.php
if (!isset($conn) || $conn->connect_error) {
    sendResponse(false, "Database connection failed: " . (isset($conn) ? $conn->connect_error : 'Unknown error'));
}

try {
    // First verify the appointment belongs to this doctor
    $checkStmt = $conn->prepare("SELECT * FROM appointments WHERE id = ? AND doctor_id = ?");
    if (!$checkStmt) {
        sendResponse(false, "Database error (prepare check): " . $conn->error);
    }
    $checkStmt->bind_param("ii", $appointmentId, $doctorId);
    if (!$checkStmt->execute()) {
        sendResponse(false, "Database error (execute check): " . $checkStmt->error);
    }
    $result = $checkStmt->get_result();
    if ($result->num_rows === 0) {
        sendResponse(false, "Appointment not found or unauthorized.");
    }
    $appointment = $result->fetch_assoc();
    // $checkStmt->close(); // Close later in finally block

    // Cancel
    if ($status === 'cancelled') {
        $stmt = $conn->prepare("UPDATE appointments SET status = 'cancelled' WHERE id = ? AND doctor_id = ?");
        if (!$stmt) { sendResponse(false, "Database error (prepare cancel): " . $conn->error); }
        $stmt->bind_param("ii", $appointmentId, $doctorId);
        if (!$stmt->execute()) { sendResponse(false, "Database error (execute cancel): " . $stmt->error); }
        // if ($stmt->affected_rows === 0) { sendResponse(false, "Could not cancel appointment or no change needed."); }
        sendResponse(true, "Appointment cancelled.");
    }

    // Propose reschedule
    if (isset($data['new_date']) && isset($data['new_time'])) {
        $new_date = $data['new_date'];
        $new_time = $data['new_time'];
        $stmt = $conn->prepare("UPDATE appointments SET reschedule_requested_by = 'doctor', new_date = ?, new_time = ?, reschedule_status = 'pending' WHERE id = ? AND doctor_id = ?");
        if (!$stmt) { sendResponse(false, "Database error (prepare reschedule): " . $conn->error); }
        $stmt->bind_param("ssii", $new_date, $new_time, $appointmentId, $doctorId);
        if (!$stmt->execute()) { sendResponse(false, "Database error (execute reschedule): " . $stmt->error); }
        sendResponse(true, "Reschedule requested.");
    }

    // Accept/decline reschedule
    if (isset($data['reschedule_action'])) {
        if ($data['reschedule_action'] === 'accept') {
            $stmt = $conn->prepare("UPDATE appointments SET date = new_date, time = new_time, reschedule_requested_by = NULL, new_date = NULL, new_time = NULL, reschedule_status = 'accepted', status = 'confirmed' WHERE id = ? AND doctor_id = ?");
            if (!$stmt) { sendResponse(false, "Database error (prepare accept reschedule): " . $conn->error); }
            $stmt->bind_param("ii", $appointmentId, $doctorId);
            if (!$stmt->execute()) { sendResponse(false, "Database error (execute accept reschedule): " . $stmt->error); }
            
            $getSlotStmt = $conn->prepare("SELECT date, time FROM appointments WHERE id = ?");
            if (!$getSlotStmt) { sendResponse(false, "Database error (prepare getSlot for unavail): " . $conn->error); }
            $getSlotStmt->bind_param("i", $appointmentId);
            if (!$getSlotStmt->execute()) { sendResponse(false, "Database error (execute getSlot for unavail): " . $getSlotStmt->error); }
            $slotResult = $getSlotStmt->get_result();
            if ($slot = $slotResult->fetch_assoc()) {
                $date = $slot['date'];
                $time = $slot['time'];
                $insStmt = $conn->prepare("INSERT IGNORE INTO doctor_unavailability (doctor_id, date, time_slot) VALUES (?, ?, ?)");
                if (!$insStmt) { sendResponse(false, "Database error (prepare insert unavail): " . $conn->error); }
                $insStmt->bind_param("iss", $doctorId, $date, $time);
                if (!$insStmt->execute()) { sendResponse(false, "Database error (execute insert unavail): " . $insStmt->error); }
                $insStmt->close();
            }
            $getSlotStmt->close();
            sendResponse(true, "Reschedule accepted.");
        } elseif ($data['reschedule_action'] === 'decline') {
            $stmt = $conn->prepare("UPDATE appointments SET reschedule_requested_by = NULL, new_date = NULL, new_time = NULL, reschedule_status = 'declined' WHERE id = ? AND doctor_id = ?");
            if (!$stmt) { sendResponse(false, "Database error (prepare decline reschedule): " . $conn->error); }
            $stmt->bind_param("ii", $appointmentId, $doctorId);
            if (!$stmt->execute()) { sendResponse(false, "Database error (execute decline reschedule): " . $stmt->error); }
            sendResponse(true, "Reschedule declined.");
        }
    }

    // Default: status update (pending/confirmed/completed)
    // This block will only be reached if $status is not 'cancelled' and no reschedule action was taken.
    if ($status && !($status === 'cancelled')) { // ensure 'cancelled' isn't re-processed
        $validStatuses = ['pending', 'confirmed', 'completed']; // 'cancelled' handled above
        if (!in_array($status, $validStatuses)) {
            sendResponse(false, "Invalid status provided for update.");
        }
        $stmt = $conn->prepare("UPDATE appointments SET status = ? WHERE id = ? AND doctor_id = ?");
        if (!$stmt) { sendResponse(false, "Database error (prepare status update): " . $conn->error); }
        $stmt->bind_param("sii", $status, $appointmentId, $doctorId);
        if (!$stmt->execute()) { sendResponse(false, "Database error (execute status update): " . $stmt->error); }
        
        if ($status === 'confirmed') {
            $getSlotStmt = $conn->prepare("SELECT date, time FROM appointments WHERE id = ?");
            if (!$getSlotStmt) { sendResponse(false, "Database error (prepare getSlot for confirm unavail): " . $conn->error); }
            $getSlotStmt->bind_param("i", $appointmentId);
            if (!$getSlotStmt->execute()) { sendResponse(false, "Database error (execute getSlot for confirm unavail): " . $getSlotStmt->error); }
            $slotResult = $getSlotStmt->get_result();
            if ($slot = $slotResult->fetch_assoc()) {
                $date = $slot['date'];
                $time = $slot['time'];
                $insStmt = $conn->prepare("INSERT IGNORE INTO doctor_unavailability (doctor_id, date, time_slot) VALUES (?, ?, ?)");
                if (!$insStmt) { sendResponse(false, "Database error (prepare insert confirm unavail): " . $conn->error); }
                $insStmt->bind_param("iss", $doctorId, $date, $time);
                if (!$insStmt->execute()) { sendResponse(false, "Database error (execute insert confirm unavail): " . $insStmt->error); }
                $insStmt->close();
            }
            $getSlotStmt->close();
        }
        sendResponse(true, "Appointment status updated successfully.", [
            'appointment_id' => $appointmentId,
            'status' => $status
        ]);
    }

    // If no other response has been sent, it means no valid action was matched.
    // This might indicate an issue with the data sent from the client
    // or a logical gap in the action handling.
    sendResponse(false, "No specific action performed. Please check request data. Status was: " . ($status ?? 'not set'));

} catch (Exception $e) {
    // Catch any other unforeseen exceptions
    sendResponse(false, "An unexpected error occurred: " . $e->getMessage());
} finally {
    // Close statements if they were initialized
    if (isset($checkStmt) && $checkStmt instanceof mysqli_stmt) {
        $checkStmt->close();
    }
    if (isset($stmt) && $stmt instanceof mysqli_stmt) {
        $stmt->close();
    }
    // $getSlotStmt and $insStmt are closed within their blocks if used.
    // It's good practice to ensure all statements are closed.
    // You might want to declare $getSlotStmt and $insStmt at the top of try block if they can be used across multiple main actions.
    
    if (isset($conn) && $conn instanceof mysqli) {
        $conn->close();
    }
} 
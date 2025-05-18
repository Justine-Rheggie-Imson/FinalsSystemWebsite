<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'doctor') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

require_once '../../dbConnect.php';

try {
    if (!isset($_FILES['file']) || !isset($_POST['patient_id'])) {
        throw new Exception('Missing required fields');
    }

    $file = $_FILES['file'];
    $patientId = intval($_POST['patient_id']);
    $doctorId = $_SESSION['user_id'];

    // Validate file
    $maxSize = 5 * 1024 * 1024; // 5MB
    if ($file['size'] > $maxSize) {
        throw new Exception('File size exceeds 5MB limit');
    }

    $allowedTypes = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'image/jpeg',
        'image/png'
    ];
    if (!in_array($file['type'], $allowedTypes)) {
        throw new Exception('Invalid file type. Allowed types: PDF, DOC, DOCX, JPG, PNG');
    }

    $uploadDir = '../../uploads/shared_files/';
    if (!file_exists($uploadDir)) {
        if (!mkdir($uploadDir, 0777, true)) {
            throw new Exception('Failed to create upload directory');
        }
    }

    if (!is_writable($uploadDir)) {
        throw new Exception('Upload directory is not writable');
    }

    $fileName = uniqid('file_') . '_' . basename($file['name']);
    $targetPath = $uploadDir . $fileName;

    if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
        throw new Exception('Failed to move uploaded file');
    }

    $stmt = $conn->prepare("
        INSERT INTO shared_files (sender_id, recipient_id, filename, filepath, upload_date)
        VALUES (?, ?, ?, ?, NOW())
    ");
    if (!$stmt) {
        throw new Exception('Database prepare failed: ' . $conn->error);
    }

    $filePath = 'uploads/shared_files/' . $fileName;
    if (!$stmt->bind_param("iiss", $doctorId, $patientId, $file['name'], $filePath)) {
        throw new Exception('Parameter binding failed: ' . $stmt->error);
    }

    if (!$stmt->execute()) {
        unlink($targetPath);
        throw new Exception('Failed to save file record: ' . $stmt->error);
    }

    echo json_encode([
        'success' => true,
        'message' => 'File uploaded successfully',
        'file' => [
            'id' => $stmt->insert_id,
            'filename' => $file['name'],
            'filepath' => '/FinalsWeb/' . $filePath,
            'upload_date' => date('Y-m-d H:i:s'),
            'sender_name' => 'You'
        ]
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?> 
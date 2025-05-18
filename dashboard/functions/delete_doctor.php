<?php
include '../../dbConnect.php';

// Validate and sanitize id
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    header("Location: ../dashboardAdmin.php?error=invalid_id");
    exit;
}

$stmt = $conn->prepare("DELETE FROM accounts WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();

header("Location: ../dashboardAdmin.php");
exit;
?>

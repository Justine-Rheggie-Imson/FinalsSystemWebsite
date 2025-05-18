<?php
include '../../dbConnect.php';

$id = $_POST['id'];
$name = $_POST['name'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$role = 'doctor';

$sql = "INSERT INTO accounts (id, fullname, email, password, role) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("sssss", $id, $name, $email, $password, $role);

if ($stmt->execute()) {
    header("Location: ../dashboardAdmin.php");
    exit;
} else {
    die("Execute failed: " . $stmt->error);
}
?>

<?php
// Securely connect to DB
if (!file_exists('dbConnect.php')) {
    die("Database connection file not found.");
}
include 'dbConnect.php';

$email = trim($_POST['email']);
$answer = strtolower(trim($_POST['security_answer']));
$newPassword = $_POST['new_password'];
$confirmPassword = $_POST['confirm_password'];

// Check password match
if ($newPassword !== $confirmPassword) {
    echo "Passwords do not match.";
    exit;
}

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Invalid email format.";
    exit;
}

// Check if email and animal match
$stmt = $conn->prepare("SELECT * FROM accounts WHERE email = ? AND LOWER(favorite_animal) = ?");
$stmt->bind_param("ss", $email, $answer);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    $update = $conn->prepare("UPDATE accounts SET password = ? WHERE email = ?");
    $update->bind_param("ss", $hashedPassword, $email);
    $update->execute();

    echo "Password updated successfully.";
} else {
    echo "Invalid email or favorite animal.";
}
?>

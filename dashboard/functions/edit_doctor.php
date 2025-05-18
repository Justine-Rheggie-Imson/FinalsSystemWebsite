<?php
include '../../dbConnect.php';

// Validate and sanitize id
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    echo '<div class="alert alert-danger">Invalid user ID.</div>';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);

    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE accounts SET fullname=?, email=?, password=? WHERE id=?");
        $stmt->bind_param("sssi", $fullname, $email, $password, $id);
    } else {
        $stmt = $conn->prepare("UPDATE accounts SET fullname=?, email=? WHERE id=?");
        $stmt->bind_param("ssi", $fullname, $email, $id);
    }

    $stmt->execute();
    $stmt->close();

    echo '<div class="alert alert-success">User updated successfully. Reloading...</div>';
    exit;
} else {
    $stmt = $conn->prepare("SELECT id, fullname, email FROM accounts WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    if (!$user) {
        echo '<div class="alert alert-danger">User not found.</div>';
        exit;
    }
}
?>

<!-- Edit User Form -->
<form method="POST" id="editDoctorForm">
    <input type="text" name="id" value="<?= htmlspecialchars($user['id']) ?>" class="form-control mb-2" readonly>
    <input type="text" name="fullname" value="<?= htmlspecialchars($user['fullname']) ?>" class="form-control mb-2" required>
    <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" class="form-control mb-2" required>
    <input type="password" name="password" class="form-control mb-2" placeholder="Leave blank to keep current password">
    <button type="submit" class="btn btn-primary">Update</button>
</form>

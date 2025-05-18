<?php
session_start();
error_log("Starting login process"); // Debug

include 'dbConnect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    error_log("POST request received"); // Debug
    
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    
    error_log("Attempting login for email: " . $email); // Debug

    $sql = "SELECT * FROM accounts WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        error_log("User found in database"); // Debug

        if (password_verify($password, $user['password'])) {
            error_log("Password verified successfully"); // Debug
            
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];  // Save the role from DB
            $_SESSION['email'] = $user['email'];
            
            error_log("Session variables set: " . print_r($_SESSION, true)); // Debug

            // Redirect based on role
            switch ($user['role']) {
                case 'admin':
                    header("Location: dashboard/dashboardAdmin.php");
                    break;
                case 'doctor':
                    // Check if doctor already exists in doctors table
                    $doctorCheckSql = "SELECT id FROM doctors WHERE account_id = ?";
                    $doctorCheckStmt = $conn->prepare($doctorCheckSql);
                    $doctorCheckStmt->bind_param("i", $user['id']);
                    $doctorCheckStmt->execute();
                    $doctorCheckResult = $doctorCheckStmt->get_result();

                    if ($doctorCheckResult->num_rows === 0) {
                        // Insert new row for this doctor
                        $insertDoctorSql = "INSERT INTO doctors (account_id) VALUES (?)";
                        $insertDoctorStmt = $conn->prepare($insertDoctorSql);
                        $insertDoctorStmt->bind_param("i", $user['id']);
                        $insertDoctorStmt->execute();
                    }
                    header("Location: dashboard/dashboardDoctor.php");
                    break;
                case 'client':
                default:
                    header("Location: dashboard/dashboardClient.php");
                    break;
            }
            exit();
        } else {
            error_log("Password verification failed"); // Debug
            $_SESSION['error'] = "Invalid email or password";
            $_SESSION['show_login'] = true; // Tell index.php to reopen modal
            header("Location: index.php");
            exit();
        }
    } else {
        error_log("No user found with that email"); // Debug
        $_SESSION['error'] = "Invalid email or password";
        $_SESSION['show_login'] = true;
        header("Location: index.php");
        exit();
    }
} else {
    error_log("Attempted direct access to login.php"); // Debug
    header("Location: index.php");
    exit();
}

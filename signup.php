<?php

include 'dbConnect.php'; // make sure this connects to your database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $animal = mysqli_real_escape_string($conn, $_POST['animal']);

    // Check if email already exists
    $check = mysqli_query($conn, "SELECT id FROM accounts WHERE email = '$email'");
    if (mysqli_num_rows($check) > 0) {
        echo "Email already registered.";
        exit;
    }

    $sql = "INSERT INTO accounts (fullname, email, password, favorite_animal) 
            VALUES ('$full_name', '$email', '$password', '$animal')";

    if (mysqli_query($conn, $sql)) {
        // After successful registration in signup.php:
        $_SESSION['register_success'] = "Registration successful! Please login with your credentials.";
        header("Location: index.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

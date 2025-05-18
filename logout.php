<?php
session_start();

// Clear all session variables
$_SESSION = array();

// Destroy the session cookie
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-3600, '/');
}

// Destroy the session
session_destroy();

// Set logout message for prompt
session_start();
$_SESSION['show_login'] = true;
$_SESSION['login_message'] = "You have been logged out successfully.";

// Redirect to index page
header("Location: index.php");
exit();
?> 
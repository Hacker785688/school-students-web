<?php
session_start();

// Redirect if not logged in
function checkLogin() {
    if(!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }
}

// Check if admin session active
function isAdmin() {
    return isset($_SESSION['is_admin']) && $_SESSION['is_admin']===true;
}
?>


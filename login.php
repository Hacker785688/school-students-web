<?php
require_once 'includes/storage.php';
session_start();

$users = readJson("data/users.json");

if ($_SERVER['REQUEST_METHOD']=='POST') {
    foreach ($users as $u) {
        if ($u['username']===$_POST['username'] &&
            password_verify($_POST['password'],$u['password_hash'])) {
            $_SESSION['user_id']=$u['id'];
            $_SESSION['username']=$u['username'];
            header("Location:index.php"); exit;
        }
    }
    $error="Invalid credentials";
}
?>


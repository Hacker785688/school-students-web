<?php
require_once 'includes/storage.php';
require_once 'includes/functions.php'; // for randomBackground()
session_start();

$bg_url = randomBackground(); // optional, for page background
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $users = readJson("data/users.json");
    $username = trim($_POST['username']);
    $password = $_POST['password'] ?? '';

    $found = false;
    foreach ($users as $u) {
        if ($u['username'] === $username && password_verify($password, $u['password_hash'])) {
            $_SESSION['user_id'] = $u['id'];
            $_SESSION['username'] = $u['username'];
            header("Location: index.php");
            exit;
        }
    }

    $error = "Invalid username or password";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Login - Student Portal</title>
<link href="assets/css/style.css" rel="stylesheet">
<style>
body { 
    background: url('<?= $bg_url ?>') no-repeat center/cover;
    font-family: Arial, sans-serif;
}
form { 
    background: rgba(255,255,255,0.9); 
    padding: 30px; 
    border-radius: 12px; 
    max-width: 350px; 
    margin: 50px auto; 
    display: flex; 
    flex-direction: column; 
}
input, button { 
    padding: 10px; 
    margin: 8px 0; 
    border-radius: 6px; 
    border: 1px solid #ccc; 
    font-size: 14px; 
}
button { 
    background: #4a90e2; 
    color: #fff; 
    border: none; 
    cursor: pointer; 
}
button:hover { background: #357ab8; }
.error { color: red; margin-top: 5px; font-size: 13px; }
</style>
</head>
<body>
<form method="POST">
    <h2>Login</h2>
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Login</button>
    <?php if ($error) echo "<p class='error'>$error</p>"; ?>
    <a href="register.php">Don't have an account? Register</a>
</form>
</body>
</html>

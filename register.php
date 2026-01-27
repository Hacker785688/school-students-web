<?php
session_start();

// Include JSON storage functions and utility functions
require_once 'includes/storage.php';
require_once 'includes/functions.php'; // for randomBackground()

$bg_url = randomBackground();
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $users = readJson("data/users.json");
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // Basic validation
    if (!$username || !$password) {
        $error = "Username and password are required";
    } else {
        // Check for existing username
        $exists = false;
        foreach ($users as $u) {
            if ($u['username'] === $username) {
                $exists = true;
                break;
            }
        }

        if ($exists) {
            $error = "Username already exists";
        } else {
            // Register new user
            $users[] = [
                "id" => nextId($users),
                "username" => $username,
                "password_hash" => password_hash($password, PASSWORD_DEFAULT)
            ];

            writeJson("data/users.json", $users);

            // Log in immediately
            $_SESSION['user_id'] = end($users)['id'];
            $_SESSION['username'] = $username;

            header("Location: index.php");
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Register - Student Portal</title>
<link href="assets/css/style.css" rel="stylesheet">
<style>
body {
    background: url('<?= $bg_url ?>') no-repeat center/cover;
    font-family: Arial, sans-serif;
}
form {
    background: rgba(255,255,255,0.95);
    padding: 30px;
    border-radius: 10px;
    width: 320px;
    margin: 80px auto;
    text-align: center;
}
input {padding: 10px; margin: 10px 0; width: 90%; border-radius: 5px; border: 1px solid #ccc;}
button {padding: 10px 20px; border:none; border-radius:5px; background:#4a90e2; color:white; cursor:pointer;}
button:hover {background:#357ab8;}
.error {color:red; margin-top:10px;}
</style>
</head>
<body>
<form method="POST">
    <h2>Register</h2>
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Register</button>
    <?php if($error): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <p><a href="login.php">Already have an account? Login</a></p>
</form>
</body>
</html>

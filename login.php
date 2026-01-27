<?php
session_start();

// Database connection
$host = 'localhost';
$user = 'portaluser';
$pass = 'StrongPassword';
$db   = 'student_portal';
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("DB Connection failed: " . $conn->connect_error);
}

// Random background
$bg_folder = 'assets/bg/';
$bg_images = glob($bg_folder . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);
$bg_url = $bg_images[array_rand($bg_images)] ?? '';

$error = '';

// Handle POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password_hash FROM users WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows) {
        $row = $res->fetch_assoc();
        if (password_verify($password, $row['password_hash'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $username;

            // Redirect before any output
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
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
<style>
* {margin:0; padding:0; box-sizing:border-box;}
body {
    font-family: 'Roboto', sans-serif;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    background: url('<?= $bg_url ?>') no-repeat center center/cover;
}
form {
    background: rgba(255,255,255,0.95);
    padding: 40px 30px;
    border-radius: 12px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.2);
    width: 320px;
    display: flex;
    flex-direction: column;
    animation: fadeIn 1s ease-out;
}
h2 {text-align:center; margin-bottom:20px; color:#333;}
input {padding:12px; margin-bottom:15px; border-radius:8px; border:1px solid #ccc; width:100%; font-size:14px;}
button {padding:12px; border:none; border-radius:8px; background-color:#4a90e2; color:white; font-weight:500; cursor:pointer; transition:0.3s;}
button:hover {background-color:#357ab8;}
.error {color:red; text-align:center; margin-top:10px; font-size:14px;}
a {text-align:center; margin-top:12px; font-size:14px; color:#4a90e2; text-decoration:none;}
a:hover {text-decoration:underline;}
@keyframes fadeIn {0% {opacity:0; transform: translateY(-15px);} 100% {opacity:1; transform: translateY(0);}}
</style>
</head>
<body>
<form method="POST">
    <h2>Login</h2>
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Login</button>
    <?php if($error) echo "<p class='error'>$error</p>"; ?>
    <a href="register.php">Don't have an account? Register</a>
</form>
</body>
</html>

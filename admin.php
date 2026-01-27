<?php
require_once 'includes/functions.php';
session_start();

$admin_pass = 'wtfh4rsh admin';
$login_error = '';

// Handle admin login
if(isset($_POST['admin_password'])){
    if(trim($_POST['admin_password']) === $admin_pass){
        $_SESSION['is_admin'] = true;
        header("Location: admin.php"); exit;
    } else {
        $login_error = "Invalid password";
    }
}

// Admin actions
if(isset($_SESSION['is_admin']) && $_SESSION['is_admin']===true){
    $sections = ['confessions','complaints','suggestions'];
    foreach($sections as $sec){
        if(isset($_POST['delete_id'])){
            deleteMessage((int)$_POST['delete_id'],$sec,null,true);
            header("Location: admin.php"); exit;
        }
    }
    // Fetch all messages
    $messages = [];
    foreach($sections as $sec){
        $messages[$sec] = fetchMessages($sec);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Panel</title>
<link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
<?php if(!isset($_SESSION['is_admin']) || $_SESSION['is_admin']!==true): ?>
<form method="POST">
<h2>Admin Login</h2>
<input type="password" name="admin_password" placeholder="Password" required>
<button type="submit">Login</button>
<?php if($login_error) echo "<p style='color:red'>$login_error</p>"; ?>
</form>
<?php else: ?>
<h2>Admin Panel - Delete Any Message</h2>
<a href="logout.php">Logout Admin</a>
<?php foreach($messages as $sec=>$msgs): ?>
<section>
<h3><?= ucfirst($sec) ?></h3>
<?php foreach($msgs as $msg): ?>
<div>
<strong><?= htmlspecialchars($msg['name']) ?>:</strong>
<p><?= htmlspecialchars($msg['message']) ?></p>
<form method="POST">
<input type="hidden" name="delete_id" value="<?= $msg['id'] ?>">
<button>Delete</button>
</form>
</div>
<?php endforeach; ?>
</section>
<?php endforeach; ?>
<?php endif; ?>
</body>
</html>

<?php
require_once 'includes/functions.php';
session_start();

// --- Admin password ---
$admin_pass = 'wtfh4rsh admin';
$login_error = '';

// --- Handle Admin Login ---
if(isset($_POST['admin_password'])){
    if(trim($_POST['admin_password']) === $admin_pass){
        $_SESSION['is_admin'] = true;
        header("Location: admin.php");
        exit;
    } else {
        $login_error = "Invalid password";
    }
}

// --- Handle Admin Logout ---
if(isset($_GET['logout'])){
    session_destroy();
    header("Location: admin.php");
    exit;
}

// --- Handle Deletion ---
if(isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true){
    if(isset($_POST['delete_id'], $_POST['delete_section'])){
        $id = (int)$_POST['delete_id'];
        $section = $_POST['delete_section'];
        deleteMessage($id, $section, null, true);
        header("Location: admin.php");
        exit;
    }

    // Fetch messages from all sections
    $sections = ['confessions','complaints','suggestions'];
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
<style>
body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 20px; }
form { margin-bottom: 20px; }
button { cursor: pointer; padding: 5px 10px; }
.message-box { background: white; padding: 10px; margin-bottom: 10px; border-radius: 5px; }
h2, h3 { margin-bottom: 10px; }
</style>
</head>
<body>

<?php if(!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true): ?>
    <h2>Admin Login</h2>
    <form method="POST">
        <input type="password" name="admin_password" placeholder="Password" required>
        <button type="submit">Login</button>
        <?php if($login_error) echo "<p style='color:red'>$login_error</p>"; ?>
    </form>
<?php else: ?>
    <h2>Admin Panel</h2>
    <a href="admin.php?logout=1">Logout Admin</a>

    <?php foreach($messages as $section => $msgs): ?>
        <section>
            <h3><?= ucfirst($section) ?></h3>
            <?php if(empty($msgs)): ?>
                <p>No messages in this section.</p>
            <?php else: ?>
                <?php foreach($msgs as $msg): ?>
                    <div class="message-box">
                        <strong><?= htmlspecialchars($msg['name']) ?>:</strong>
                        <p><?= htmlspecialchars($msg['message']) ?></p>
                        <small><?= $msg['submitted_at'] ?></small>
                        <form method="POST" style="margin-top:5px;">
                            <input type="hidden" name="delete_id" value="<?= $msg['id'] ?>">
                            <input type="hidden" name="delete_section" value="<?= $section ?>">
                            <button>Delete</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>
    <?php endforeach; ?>
<?php endif; ?>

</body>
</html>

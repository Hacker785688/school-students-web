<?php
require_once 'includes/auth.php';
require_once 'includes/functions.php';
checkLogin();

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

$valid_sections = ['confessions'=>'Confession','complaints'=>'Complaint','suggestions'=>'Suggestion'];

// Handle POST
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Delete message
    if(isset($_POST['delete_id'], $_POST['delete_section'])) {
        $sec = $_POST['delete_section'];
        $id  = (int)$_POST['delete_id'];
        if(array_key_exists($sec, $valid_sections))
            deleteMessage($id, $sec, $user_id, false); // change to true if admin
        header("Location:index.php"); exit;
    }

    // New message
    $section = $_POST['section'] ?? '';
    $name = trim($_POST['name'] ?? $username);
    $msg = trim($_POST['message'] ?? '');
    if($msg && array_key_exists($section, $valid_sections))
        insertMessage($section, $name, $msg, $user_id);
    header("Location:index.php"); exit;
}

// Fetch messages
$confessions = fetchMessages('confessions');
$complaints  = fetchMessages('complaints');
$suggestions = fetchMessages('suggestions');

// Random background
$bg_url = randomBackground();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Student Portal</title>
<link href="assets/css/style.css" rel="stylesheet">
<script src="assets/js/scripts.js"></script>
<style>
body{background:url('<?= $bg_url ?>') no-repeat center/cover;}
</style>
</head>
<body>
<header>
<h1>Student Voice Portal</h1>
<form method="POST" action="logout.php"><button>Logout</button></form>
</header>

<section>
<h2>Submit Feedback</h2>
<?php foreach($valid_sections as $key=>$label): ?>
<form method="POST">
<input type="hidden" name="section" value="<?= $key ?>">
<input type="text" name="name" placeholder="Your Name" value="<?= htmlspecialchars($username) ?>">
<textarea name="message" placeholder="Your <?= strtolower($label) ?>..." required></textarea>
<button type="submit">Submit <?= $label ?></button>
</form>
<?php endforeach; ?>
</section>

<?php 
$sections_data = [
    'Confessions' => $confessions,
    'Complaints'  => $complaints,
    'Suggestions' => $suggestions
];
?>

<?php foreach($sections_data as $title => $messages): ?>
<section>
<h2><?= $title ?></h2>
<div class="scroll-box">
<?php foreach($messages as $i => $msg): ?>
<div class="message-box <?= $i>=5?'hidden':'' ?>">
<strong><?= htmlspecialchars($msg['name']) ?>:</strong>
<p><?= htmlspecialchars($msg['message']) ?></p>
<small><?= $msg['submitted_at'] ?></small>
<?php if($msg['user_id'] == $user_id): ?>
<form method="POST">
<input type="hidden" name="delete_id" value="<?= $msg['id'] ?>">
<input type="hidden" name="delete_section" value="<?= strtolower($title) ?>">
<button class="delete-btn">Delete</button>
</form>
<?php endif; ?>
</div>
<?php endforeach; ?>
<?php if(count($messages) > 5): ?>
<button class="show-all-btn" onclick="showAll(this)">Show All</button>
<?php endif; ?>
</div>
</section>
<?php endforeach; ?>
</body>
</html>

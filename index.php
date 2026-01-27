<?php
require_once 'includes/auth.php';
require_once 'includes/functions.php';
checkLogin();

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// Valid sections
$valid_sections = [
    'confessions' => 'Confession',
    'complaints'  => 'Complaint',
    'suggestions' => 'Suggestion'
];

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Delete message
    if (isset($_POST['delete_id'], $_POST['delete_section'])) {
        deleteMessage((int)$_POST['delete_id'], $_POST['delete_section'], $user_id);
        header("Location: index.php");
        exit;
    }

    // Insert new message
    if (!empty($_POST['message']) && isset($_POST['section'])) {
        insertMessage(
            $_POST['section'],
            trim($_POST['name'] ?? $username),
            trim($_POST['message']),
            $user_id
        );
        header("Location: index.php");
        exit;
    }
}

// Fetch messages
$confessions = fetchMessages('confessions');
$complaints  = fetchMessages('complaints');
$suggestions = fetchMessages('suggestions');

// Pick random background
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
body {
    background: url('<?= $bg_url ?>') no-repeat center/cover;
    font-family: Arial, sans-serif;
}
header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    background: rgba(255,255,255,0.85);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}
section {
    margin: 20px auto;
    max-width: 800px;
    background: rgba(255,255,255,0.9);
    padding: 20px;
    border-radius: 10px;
}
.message-box {
    border-bottom: 1px solid #ddd;
    padding: 10px 0;
}
.message-box:last-child { border-bottom: none; }
textarea { width: 100%; min-height: 60px; margin-bottom: 10px; }
button { padding: 8px 12px; border: none; border-radius: 6px; background: #4a90e2; color: #fff; cursor: pointer; }
button:hover { background: #357ab8; }
</style>
</head>
<body>

<header>
<h1 id="portal-title">Student Voice Portal - DAV BINA</h1>
<form method="POST" action="logout.php">
    <button>Logout</button>
</form>
</header>

<script>
// Random color animation for header text
function randomColor() {
    const r = Math.floor(Math.random() * 256);
    const g = Math.floor(Math.random() * 256);
    const b = Math.floor(Math.random() * 256);
    return `rgb(${r},${g},${b})`;
}

const title = document.getElementById('portal-title');
setInterval(() => {
    title.style.color = randomColor();
}, 500); // change color every 0.5s
</script>

<section>
<h2>Submit Feedback</h2>
<?php foreach ($valid_sections as $key => $label): ?>
<form method="POST">
    <input type="hidden" name="section" value="<?= $key ?>">
    <input type="text" name="name" value="<?= htmlspecialchars($username) ?>" placeholder="Your Name">
    <textarea name="message" placeholder="Write your <?= strtolower($label) ?>..." required></textarea>
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

<?php foreach ($sections_data as $title => $messages): ?>
<section>
<h2><?= $title ?></h2>
<?php foreach ($messages as $m): ?>
<div class="message-box">
    <strong><?= htmlspecialchars($m['name']) ?>:</strong>
    <p><?= htmlspecialchars($m['message']) ?></p>
    <small><?= $m['submitted_at'] ?></small>
    <?php if ($m['user_id'] == $user_id): ?>
    <form method="POST">
        <input type="hidden" name="delete_id" value="<?= $m['id'] ?>">
        <input type="hidden" name="delete_section" value="<?= strtolower($title) ?>">
        <button>Delete</button>
    </form>
    <?php endif; ?>
</div>
<?php endforeach; ?>
</section>
<?php endforeach; ?>

</body>
</html>

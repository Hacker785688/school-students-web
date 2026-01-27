<?php
require_once 'includes/auth.php';
require_once 'includes/functions.php';
checkLogin();

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

$valid_sections = [
    'confessions'=>'Confession',
    'complaints'=>'Complaint',
    'suggestions'=>'Suggestion'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_id'], $_POST['delete_section'])) {
        deleteMessage((int)$_POST['delete_id'], $_POST['delete_section'], $user_id);
        header("Location:index.php"); exit;
    }

    if (!empty($_POST['message']) && isset($_POST['section'])) {
        insertMessage(
            $_POST['section'],
            trim($_POST['name'] ?? $username),
            trim($_POST['message']),
            $user_id
        );
        header("Location:index.php"); exit;
    }
}

$confessions = fetchMessages('confessions');
$complaints  = fetchMessages('complaints');
$suggestions = fetchMessages('suggestions');
$bg_url = randomBackground();
?>
<!DOCTYPE html>
<html>
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
<input type="text" name="name" value="<?= htmlspecialchars($username) ?>">
<textarea name="message" required></textarea>
<button>Submit <?= $label ?></button>
</form>
<?php endforeach; ?>
</section>

<?php foreach([
'Confessions'=>$confessions,
'Complaints'=>$complaints,
'Suggestions'=>$suggestions
] as $title=>$msgs): ?>

<section>
<h2><?= $title ?></h2>
<?php foreach($msgs as $m): ?>
<div class="message-box">
<strong><?= htmlspecialchars($m['name']) ?>:</strong>
<p><?= htmlspecialchars($m['message']) ?></p>
<small><?= $m['submitted_at'] ?></small>
<?php if($m['user_id']==$user_id): ?>
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

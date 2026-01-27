<?php
session_start();
require_once 'includes/storage.php';
require_once 'includes/functions.php'; // <-- needed for randomBackground()

$bg_url = randomBackground();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $users = readJson("data/users.json");
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Check if username exists
    foreach ($users as $u) {
        if ($u['username'] === $username) {
            $error = "Username already exists";
            break;
        }
    }

    // If no error, register
    if (!$error) {
        $users[] = [
            "id" => nextId($users),
            "username" => $username,
            "password_hash" => password_hash($password, PASSWORD_DEFAULT)
        ];
        writeJson("data/users.json", $users);

        $_SESSION['user_id'] = end($users)['id'];
        $_SESSION['username'] = $username;

        header("Location: index.php");
        exit;
    }
}
?>

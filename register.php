<?php
require_once 'includes/storage.php';
session_start();

$bg_url = randomBackground();

if ($_SERVER['REQUEST_METHOD']=='POST') {
    $users = readJson("data/users.json");
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    foreach ($users as $u) {
        if ($u['username']===$username) $error="Username exists";
    }

    if (!isset($error)) {
        $users[] = [
            "id"=>nextId($users),
            "username"=>$username,
            "password_hash"=>password_hash($password,PASSWORD_DEFAULT)
        ];
        writeJson("data/users.json",$users);
        $_SESSION['user_id']=end($users)['id'];
        $_SESSION['username']=$username;
        header("Location:index.php"); exit;
    }
}
?>
<!-- UI unchanged -->

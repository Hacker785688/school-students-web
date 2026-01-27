<?php
// Database config
$host = 'nozomi.proxy.rlwy.net';
$user = 'root';
$pass = 'XooMYlxpdqpTjKKhPUVqnqmoHXIjCrtU';
$db   = 'railway';
$port = 36241;

$conn = new mysqli($host, $user, $pass, $db, $port);
if($conn->connect_error) die("DB Connection failed: ".$conn->connect_error);


// Auto-create tables if not exist
$conn->query("CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) UNIQUE,
    password_hash VARCHAR(255)
)");

$sections = ['confessions','complaints','suggestions'];

foreach ($sections as $sec) {
    $conn->query("CREATE TABLE IF NOT EXISTS $sec (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100),
        message TEXT NOT NULL,
        user_id INT NOT NULL,
        submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
}
?>

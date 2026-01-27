<?php
// JSON storage paths
define('DATA_DIR', __DIR__ . '/../data');

// Ensure data directory exists
if (!is_dir(DATA_DIR)) {
    mkdir(DATA_DIR, 0755, true);
}

// Sections to store messages
$sections = ['confessions', 'complaints', 'suggestions'];

// Ensure each section file exists
foreach ($sections as $section) {
    $file = DATA_DIR . "/$section.json";
    if (!file_exists($file)) {
        file_put_contents($file, json_encode([]));
    }
}

// Ensure users file exists
$users_file = DATA_DIR . "/users.json";
if (!file_exists($users_file)) {
    file_put_contents($users_file, json_encode([]));
}
?>

<?php
require_once 'config.php';

// Valid sections whitelist
$valid_sections = ['confessions','complaints','suggestions'];

// Fetch messages safely
function fetchMessages($section) {
    global $conn, $valid_sections;
    if(!in_array($section, $valid_sections)) return [];

    $stmt = $conn->prepare("SELECT * FROM $section ORDER BY submitted_at DESC");
    $stmt->execute();
    $res = $stmt->get_result();
    return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
}

// Insert a new message safely
function insertMessage($section, $name, $message, $user_id) {
    global $conn, $valid_sections;
    if(!in_array($section, $valid_sections)) return false;

    $stmt = $conn->prepare("INSERT INTO $section (name,message,user_id) VALUES (?,?,?)");
    $stmt->bind_param("ssi", $name, $message, $user_id);
    return $stmt->execute();
}

// Delete message: user can delete own, admin can delete any
function deleteMessage($id, $section, $user_id, $isAdmin=false) {
    global $conn, $valid_sections;
    if(!in_array($section, $valid_sections)) return false;

    if($isAdmin) {
        $stmt = $conn->prepare("DELETE FROM $section WHERE id=?");
        $stmt->bind_param("i", $id);
    } else {
        $stmt = $conn->prepare("DELETE FROM $section WHERE id=? AND user_id=?");
        $stmt->bind_param("ii", $id, $user_id);
    }
    return $stmt->execute();
}

// Pick random background from folder
function randomBackground($folder='assets/bg') {
    $images = glob("$folder/*.{jpg,jpeg,png,gif}", GLOB_BRACE);
    return $images ? $images[array_rand($images)] : '';
}
?>

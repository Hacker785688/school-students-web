<?php
require_once 'config.php';

// Fetch messages for section
function fetchMessages($section) {
    global $conn;
    $res = $conn->query("SELECT * FROM $section ORDER BY submitted_at DESC");
    return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
}

// Insert a new message
function insertMessage($section, $name, $message, $user_id) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO $section (name,message,user_id) VALUES (?,?,?)");
    $stmt->bind_param("ssi",$name,$message,$user_id);
    $stmt->execute();
}

// Delete message: user only deletes own, admin can delete any
function deleteMessage($id, $section, $user_id, $isAdmin=false) {
    global $conn;
    if($isAdmin) {
        $stmt = $conn->prepare("DELETE FROM $section WHERE id=?");
        $stmt->bind_param("i",$id);
    } else {
        $stmt = $conn->prepare("DELETE FROM $section WHERE id=? AND user_id=?");
        $stmt->bind_param("ii",$id,$user_id);
    }
    $stmt->execute();
}

// Pick random background from folder
function randomBackground($folder='assets/bg') {
    $images = glob("$folder/*.{jpg,jpeg,png,gif}", GLOB_BRACE);
    return $images ? $images[array_rand($images)] : '';
}
?>

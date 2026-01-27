<?php
require_once 'storage.php';

function fetchMessages($section) {
    return array_reverse(readJson("data/$section.json"));
}

function insertMessage($section, $name, $message, $user_id) {
    $file = "data/$section.json";
    $data = readJson($file);

    $data[] = [
        "id" => nextId($data),
        "user_id" => $user_id,
        "name" => $name,
        "message" => $message,
        "submitted_at" => date("Y-m-d H:i:s")
    ];

    writeJson($file, $data);
}

function deleteMessage($id, $section, $user_id, $isAdmin=false) {
    $file = "data/$section.json";
    $data = readJson($file);

    $data = array_filter($data, function($m) use ($id, $user_id, $isAdmin) {
        if ($isAdmin) return $m['id'] != $id;
        return !($m['id'] == $id && $m['user_id'] == $user_id);
    });

    writeJson($file, array_values($data));
}

function randomBackground($folder='assets/bg') {
    $imgs = glob("$folder/*.{jpg,jpeg,png,gif}", GLOB_BRACE);
    return $imgs ? $imgs[array_rand($imgs)] : '';
}

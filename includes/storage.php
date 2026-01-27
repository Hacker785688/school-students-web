<?php
// File: includes/storage.php

/**
 * Read a JSON file and return as an array.
 * @param string $file
 * @return array
 */
function readJson(string $file): array {
    if (!file_exists($file)) return [];
    $json = file_get_contents($file);
    $data = json_decode($json, true);
    return is_array($data) ? $data : [];
}

/**
 * Write an array to a JSON file.
 * @param string $file
 * @param array $data
 * @return bool
 */
function writeJson(string $file, array $data): bool {
    $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    return file_put_contents($file, $json) !== false;
}

/**
 * Generate the next ID for a JSON array.
 * @param array $data
 * @return int
 */
function nextId(array $data): int {
    if (empty($data)) return 1;
    $ids = array_column($data, 'id');
    return max($ids) + 1;
}

/**
 * Pick a random background image from a folder.
 * @param string $folder
 * @return string
 */
function randomBackground(string $folder = 'assets/bg'): string {
    $imgs = glob("$folder/*.{jpg,jpeg,png,gif}", GLOB_BRACE);
    return $imgs ? $imgs[array_rand($imgs)] : '';
}
?>

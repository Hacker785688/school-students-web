<?php
// File: includes/storage.php

/**
 * GitHub configuration (from Render Environment Variables)
 */
define('GITHUB_USER', getenv('GITHUB_USER'));
define('GITHUB_REPO', getenv('GITHUB_REPO'));
define('GITHUB_TOKEN', getenv('GITHUB_TOKEN'));

/**
 * Internal: GitHub API request
 */
function githubRequest(string $url, string $method = 'GET', array $payload = null): array {
    $ch = curl_init($url);

    $headers = [
        "Authorization: Bearer " . GITHUB_TOKEN,
        "User-Agent: PHP-Storage",
        "Accept: application/vnd.github+json"
    ];

    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_HTTPHEADER => $headers
    ]);

    if ($payload !== null) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, array_merge($headers, [
            "Content-Type: application/json"
        ]));
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    }

    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true) ?? [];
}

/**
 * Read a JSON file from GitHub repo
 * @param string $file (example: data/students.json)
 * @return array
 */
function readJson(string $file): array {
    $url = "https://api.github.com/repos/"
        . GITHUB_USER . "/"
        . GITHUB_REPO
        . "/contents/" . $file;

    $result = githubRequest($url);

    if (!isset($result['content'])) {
        return [];
    }

    $json = base64_decode($result['content']);
    $data = json_decode($json, true);

    return is_array($data) ? $data : [];
}

/**
 * Write an array to a JSON file in GitHub repo
 * @param string $file
 * @param array $data
 * @return bool
 */
function writeJson(string $file, array $data): bool {
    $url = "https://api.github.com/repos/"
        . GITHUB_USER . "/"
        . GITHUB_REPO
        . "/contents/" . $file;

    // Step 1: get current SHA
    $current = githubRequest($url);
    if (!isset($current['sha'])) {
        return false;
    }

    // Step 2: update file
    $payload = [
        "message" => "Update $file",
        "content" => base64_encode(
            json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        ),
        "sha" => $current['sha']
    ];

    $result = githubRequest($url, 'PUT', $payload);

    return isset($result['commit']);
}

/**
 * Generate the next ID for a JSON array.
 * (unchanged – works the same)
 */
function nextId(array $data): int {
    if (empty($data)) return 1;
    $ids = array_column($data, 'id');
    return max($ids) + 1;
}

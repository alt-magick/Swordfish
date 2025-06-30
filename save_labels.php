<?php
// save_labels.php

// Expect JSON POST with "path" and "xmlContent"
$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['path']) || !isset($data['xmlContent'])) {
    http_response_code(400);
    echo "Invalid request";
    exit;
}

// Security check: prevent directory traversal attacks
$path = $data['path'];
if (strpos($path, '..') !== false) {
    http_response_code(400);
    echo "Invalid file path";
    exit;
}

// You may want to adjust base directory here
$baseDir = __DIR__ . DIRECTORY_SEPARATOR;
$filePath = realpath($baseDir) . DIRECTORY_SEPARATOR . $path;

// Make sure directory exists and is writable
$dirName = dirname($filePath);
if (!is_dir($dirName) || !is_writable($dirName)) {
    http_response_code(500);
    echo "Directory not writable";
    exit;
}

// Save the XML content
if (file_put_contents($filePath, $data['xmlContent']) === false) {
    http_response_code(500);
    echo "Failed to save file";
    exit;
}

http_response_code(200);
echo "File saved";
?>

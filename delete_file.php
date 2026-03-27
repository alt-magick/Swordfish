<?php
// delete_file.php
header('Content-Type: application/json');

// Get raw POST data
$input = json_decode(file_get_contents('php://input'), true);
if (!isset($input['path']) || empty($input['path'])) {
    echo json_encode(['success' => false, 'error' => 'No file specified']);
    exit;
}

$path = $input['path'];

// Security: prevent deleting files outside the allowed root
$rootDir = __DIR__; // adjust if your files are in a subfolder, e.g., __DIR__.'/files'
$fullPath = realpath($path);

if (!$fullPath || strpos($fullPath, $rootDir) !== 0) {
    echo json_encode(['success' => false, 'error' => 'Invalid file path']);
    exit;
}

// Check if file exists
if (!file_exists($fullPath)) {
    echo json_encode(['success' => false, 'error' => 'File does not exist']);
    exit;
}

// Delete file
if (is_file($fullPath)) {
    if (unlink($fullPath)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to delete file']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Path is not a file']);
}

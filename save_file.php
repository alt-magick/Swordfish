<?php
// save_file.php
header('Content-Type: application/json');

// Get the raw POST data
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!isset($data['path']) || !isset($data['content'])) {
    echo json_encode(['success' => false, 'error' => 'Missing path or content']);
    exit;
}

// Sanitize path to prevent directory traversal
$path = $data['path'];
$path = str_replace(['..', "\0"], '', $path); // basic sanitization

// Ensure directories exist
$dir = dirname($path);
if (!is_dir($dir)) {
    mkdir($dir, 0777, true);
}

// Attempt to write the file
$result = file_put_contents($path, $data['content']);

if ($result === false) {
    echo json_encode(['success' => false, 'error' => 'Failed to write file']);
} else {
    echo json_encode(['success' => true]);
}
?>

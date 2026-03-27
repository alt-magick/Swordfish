<?php
// Read POST data
$data = json_decode(file_get_contents('php://input'), true);
$path = $data['path'] ?? '';

if(!$path) {
    echo json_encode(['success' => false, 'error' => 'No file path provided']);
    exit;
}

// Make sure directory exists
$dir = dirname($path);
if(!is_dir($dir) && $dir !== '.') {
    mkdir($dir, 0777, true); // recursively create folders if needed
}

// Create file
if(file_put_contents($path, '') !== false){
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to create file']);
}
?>

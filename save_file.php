<?php
header('Content-Type: text/plain');

// Read JSON input
$input = file_get_contents('php://input');
if (!$input) {
    echo "Error: No input received!";
    exit;
}

$data = json_decode($input, true);
if (!$data || !isset($data['filename']) || !isset($data['content'])) {
    echo "Error: Invalid JSON or missing 'filename' or 'content'!";
    exit;
}

$filename = $data['filename']; // keep relative path
$content = $data['content'];

// Sanitize to prevent path traversal
$filename = str_replace(['..', "\0"], '', $filename);

$dir = __DIR__; // base directory
$filepath = $dir . DIRECTORY_SEPARATOR . $filename;

// Ensure subdirectories exist
$subdir = dirname($filepath);
if (!is_dir($subdir)) {
    if (!mkdir($subdir, 0777, true)) {
        echo "Error: Could not create directory '$subdir'.";
        exit;
    }
}

// Check if file exists
if (!file_exists($filepath)) {
    echo "Error: File '$filename' does not exist!";
    exit;
}

// Attempt to write
$result = @file_put_contents($filepath, $content);
if ($result === false) {
    echo "Error: Could not write to '$filename'. Check permissions!";
    exit;
}

echo "File '$filename' saved successfully.";
?>

<?php
header('Content-Type: text/plain');

// Read JSON input
$input = file_get_contents('php://input');
if (!$input) {
    echo "Error: No input received!";
    exit;
}

$data = json_decode($input, true);
if (!$data || !isset($data['filename'])) {
    echo "Error: Invalid JSON or 'filename' missing!";
    exit;
}

$filename = basename($data['filename']); // sanitize filename
$dir = __DIR__; // current directory
$fullPath = $dir . DIRECTORY_SEPARATOR . $filename;

// Debugging info
error_log("Attempting to delete file: $fullPath");

// Check if file exists
if (!file_exists($fullPath)) {
    echo "Error: File '$filename' does not exist!";
    exit;
}

// Attempt to delete
$result = @unlink($fullPath);
if (!$result) {
    echo "Error: Could not delete file '$filename'. Check permissions!";
    exit;
}

echo "File '$filename' deleted successfully.";
?>

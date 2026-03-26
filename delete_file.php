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
    echo "Error: Invalid JSON or missing 'filename'!";
    exit;
}

$filename = $data['filename'];

// Prevent directory traversal attacks
$filename = str_replace(['..', "\0"], '', $filename);
$filepath = __DIR__ . DIRECTORY_SEPARATOR . $filename;

if (!file_exists($filepath)) {
    echo "Error: File '$filename' does not exist!";
    exit;
}

// Attempt to delete file
if (!is_writable($filepath)) {
    echo "Error: File '$filename' is not writable!";
    exit;
}

if (@unlink($filepath)) {
    echo "File '$filename' deleted successfully.";
} else {
    echo "Error: Could not delete '$filename'. Check permissions!";
}
?>

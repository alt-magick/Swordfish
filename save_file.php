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

$filename = basename($data['filename']); // sanitize filename
$content = $data['content'];
$dir = __DIR__; // current directory
$filepath = $dir . DIRECTORY_SEPARATOR . $filename;

// Debugging info
error_log("Attempting to save file: $filepath");

// Check if file exists
if (!file_exists($filepath)) {
    // If you want to allow saving new files, comment out the next two lines
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

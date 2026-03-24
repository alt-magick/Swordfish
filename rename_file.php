<?php
header('Content-Type: text/plain');

// Read JSON input
$input = file_get_contents('php://input');
if (!$input) {
    echo "Error: No input received!";
    exit;
}

$data = json_decode($input, true);
if (!$data || !isset($data['oldname']) || !isset($data['newname'])) {
    echo "Error: Invalid JSON or missing 'oldname' or 'newname'!";
    exit;
}

$oldname = basename($data['oldname']); // sanitize filenames
$newname = basename($data['newname']);
$dir = __DIR__; // current directory

$oldPath = $dir . DIRECTORY_SEPARATOR . $oldname;
$newPath = $dir . DIRECTORY_SEPARATOR . $newname;

// Debugging info
error_log("Attempting to rename file: $oldPath -> $newPath");

// Check if old file exists
if (!file_exists($oldPath)) {
    echo "Error: File '$oldname' does not exist!";
    exit;
}

// Attempt to rename
$result = @rename($oldPath, $newPath);
if (!$result) {
    echo "Error: Could not rename '$oldname' to '$newname'. Check permissions!";
    exit;
}

echo "File '$oldname' renamed to '$newname' successfully.";
?>

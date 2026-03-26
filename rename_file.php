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

$oldname = $data['oldname'];
$newname = $data['newname'];

// Sanitize filenames to prevent directory traversal
$oldname = str_replace(['..', "\0"], '', $oldname);
$newname = str_replace(['..', "\0"], '', $newname);

$oldpath = __DIR__ . DIRECTORY_SEPARATOR . $oldname;
$newpath = __DIR__ . DIRECTORY_SEPARATOR . $newname;

if (!file_exists($oldpath)) {
    echo "Error: File '$oldname' does not exist!";
    exit;
}

if (file_exists($newpath)) {
    echo "Error: File '$newname' already exists!";
    exit;
}

// Attempt to rename
if (@rename($oldpath, $newpath)) {
    echo "File '$oldname' renamed to '$newname' successfully.";
} else {
    echo "Error: Could not rename '$oldname'. Check permissions!";
}
?>

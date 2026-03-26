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
$dir = __DIR__;

// Check if it's a folder (ends with '/')
if (substr($filename, -1) === '/') {
    $folderPath = $dir . DIRECTORY_SEPARATOR . rtrim($filename, '/');
    if (!is_dir($folderPath)) {
        if (mkdir($folderPath, 0777, true)) {
            echo "Folder '$filename' created successfully.";
        } else {
            echo "Error: Could not create folder '$filename'. Check permissions!";
        }
    } else {
        echo "Folder '$filename' already exists.";
    }
    exit;
}

// Otherwise, it's a file
$filepath = $dir . DIRECTORY_SEPARATOR . $filename;

// Create parent directories if needed
$parentDir = dirname($filepath);
if (!is_dir($parentDir)) {
    if (!mkdir($parentDir, 0777, true)) {
        echo "Error: Could not create directory '$parentDir'";
        exit;
    }
}

// Create the file if it doesn't exist
if (!file_exists($filepath)) {
    if (file_put_contents($filepath, "") !== false) {
        echo "File '$filename' created successfully.";
    } else {
        echo "Error: Could not create file '$filename'. Check permissions!";
    }
} else {
    echo "File '$filename' already exists.";
}
?>

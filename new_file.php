<?php
header('Content-Type: text/plain');

// Read JSON input
$input = file_get_contents('php://input');
if(!$input){
    echo "Error: No input received!";
    exit;
}

$data = json_decode($input, true);
if(!$data || !isset($data['filename'])){
    echo "Error: Invalid JSON or 'filename' missing!";
    exit;
}

$filename = basename($data['filename']); // sanitize
$dir = __DIR__; // current directory
$fullPath = $dir . DIRECTORY_SEPARATOR . $filename;

// Debugging info
error_log("Attempting to create new file: $fullPath");

// Check if file already exists
if(file_exists($fullPath)){
    echo "Error: File '$filename' already exists!";
    exit;
}

// Attempt to create file
$result = @file_put_contents($fullPath, "");
if($result === false){
    echo "Error: Could not create file '$filename'. Check permissions!";
    exit;
}

echo "File '$filename' created successfully.";
?>

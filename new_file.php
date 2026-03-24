<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $filename = $_POST['filename'] ?? '';
    if (!$filename) {
        echo "Missing filename";
        exit;
    }

    $filename = basename($filename);

    if (file_exists($filename)) {
        echo "File already exists";
        exit;
    }

    if (file_put_contents($filename, "") !== false) {
        echo "OK";
    } else {
        echo "Failed to create file";
    }
} else {
    echo "Invalid request method";
}
?>

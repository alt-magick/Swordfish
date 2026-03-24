<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $filename = $_POST['filename'] ?? '';
    if (!$filename) {
        echo "Missing filename";
        exit;
    }

    $filename = basename($filename);

    if (!file_exists($filename)) {
        echo "File does not exist";
        exit;
    }

    if (unlink($filename)) {
        echo "OK";
    } else {
        echo "Failed to delete file";
    }
} else {
    echo "Invalid request method";
}
?>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $filename = $_POST['filename'] ?? '';
    $content = $_POST['content'] ?? '';

    if (!$filename) {
        echo "Missing filename";
        exit;
    }

    // Sanitize filename to prevent path traversal
    $filename = basename($filename);

    if (file_put_contents($filename, $content) !== false) {
        echo "OK";
    } else {
        echo "Failed to save file";
    }
} else {
    echo "Invalid request method";
}
?>

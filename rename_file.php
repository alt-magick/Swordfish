<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $oldname = $_POST['oldname'] ?? '';
    $newname = $_POST['newname'] ?? '';

    if (!$oldname || !$newname) {
        echo "Missing filenames";
        exit;
    }

    $oldname = basename($oldname);
    $newname = basename($newname);

    if (!file_exists($oldname)) {
        echo "Old file does not exist";
        exit;
    }

    if (file_exists($newname)) {
        echo "New file already exists";
        exit;
    }

    if (rename($oldname, $newname)) {
        echo "OK";
    } else {
        echo "Failed to rename file";
    }
} else {
    echo "Invalid request method";
}
?>

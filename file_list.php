<?php
header('Content-Type: application/json');

// Directory to scan
$dir = __DIR__;

// Get all .txt files
$files = array();
foreach (scandir($dir) as $file) {
    if (is_file($dir . DIRECTORY_SEPARATOR . $file) && pathinfo($file, PATHINFO_EXTENSION) === 'txt') {
        $files[] = $file;
    }
}

// Sort files alphabetically
sort($files, SORT_NATURAL | SORT_FLAG_CASE);

// Output JSON
echo json_encode($files);
?>

<?php
header('Content-Type: application/json');

// Set your main directory here (relative or absolute)
$mainDir = __DIR__;  

$path = isset($_GET['path']) ? $_GET['path'] : '';
$fullPath = realpath($mainDir . '/' . $path);

// Security: prevent navigating above mainDir
if (!$fullPath || strpos($fullPath, $mainDir) !== 0) {
    echo json_encode([]);
    exit;
}

$files = scandir($fullPath);
$dirs = [];
$txtFiles = [];

foreach ($files as $file) {
    if ($file === '.' || $file === '..') continue;

    $fullFile = $fullPath . '/' . $file;

    if (is_dir($fullFile)) {
        $dirs[] = $file . '/'; // add slash for folders
    } elseif (is_file($fullFile) && strtolower(pathinfo($file, PATHINFO_EXTENSION)) === 'txt') {
        $txtFiles[] = $file;
    }
}

// Add ../ if not in main directory
if (realpath($fullPath) != realpath($mainDir)) {
    array_unshift($dirs, '../');
}

// Merge directories first, then txt files
$result = array_merge($dirs, $txtFiles);

echo json_encode($result);

<?php

header('Content-Type: application/json');

$action = $_GET['action'] ?? '';

if ($action === 'list') {
    $dir = $_GET['dir'] ?? '.';
    if (!is_dir($dir)) {
        echo json_encode(['error' => 'Directory not found']);
        exit;
    }
    $files = scandir($dir);
    $result = [];
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') continue;
        $path = $dir . '/' . $file;
        $result[$file] = [
            'type' => is_dir($path) ? 'dir' : 'file',
            'size' => is_file($path) ? filesize($path) : 0,
        ];
    }
    echo json_encode($result);
    exit;
}

if ($action === 'read') {
    $file = $_GET['file'] ?? '';
    if (!is_file($file)) {
        echo json_encode(['error' => 'File not found']);
        exit;
    }
    echo json_encode(['content' => file_get_contents($file)]);
    exit;
}

if ($action === 'save') {
    $data = json_decode(file_get_contents('php://input'), true);
    $file = $data['file'] ?? '';
    $content = $data['content'] ?? '';
    if (!$file) {
        echo json_encode(['error' => 'Invalid file']);
        exit;
    }
    file_put_contents($file, $content);
    echo json_encode(['success' => true]);
    exit;
}

if ($action === 'create') {
    $data = json_decode(file_get_contents('php://input'), true);
    $file = $data['file'] ?? '';
    if (!$file) {
        echo json_encode(['error' => 'Invalid file']);
        exit;
    }

    $baseDir = realpath(__DIR__ . '/home/projects');  // adjust this to your root folder
    $fullPath = __DIR__ . '/' . $file;

    // Normalize and resolve the path
    $realFullPath = realpath(dirname($fullPath));
    if ($realFullPath === false) {
        // Directory doesn't exist yet, build path manually
        $realFullPath = $baseDir . '/' . $file;
    } else {
        $realFullPath .= '/' . basename($file);
    }

    // Security check: file must be inside base directory
    if (strpos(realpath(dirname($realFullPath)), $baseDir) !== 0) {
        echo json_encode(['error' => 'Invalid file path']);
        exit;
    }

    // Create directory if it doesn't exist
    $dirPath = dirname($realFullPath);
    if (!is_dir($dirPath)) {
        mkdir($dirPath, 0777, true);
    }

    if (file_exists($realFullPath)) {
        echo json_encode(['error' => 'File already exists']);
        exit;
    }

    $result = file_put_contents($realFullPath, '');
    if ($result === false) {
        echo json_encode(['error' => 'Failed to create file']);
    } else {
        echo json_encode(['success' => true]);
    }
    exit;
}

if ($action === 'mkdir') {
    $data = json_decode(file_get_contents('php://input'), true);
    $dir = $data['dir'] ?? '';
    if (!$dir) {
        echo json_encode(['error' => 'Invalid directory name']);
        exit;
    }

    $baseDir = realpath(__DIR__ . '/home/projects');  // your base root folder
    $fullPath = __DIR__ . '/' . $dir;

    // Normalize path
    $realFullPath = realpath(dirname($fullPath));
    if ($realFullPath === false) {
        // Directory path doesn't exist yet
        $realFullPath = $baseDir . '/' . $dir;
    } else {
        $realFullPath .= '/' . basename($dir);
    }

    // Security check: directory must be inside baseDir
    if (strpos(realpath(dirname($realFullPath)), $baseDir) !== 0) {
        echo json_encode(['error' => 'Invalid directory path']);
        exit;
    }

    // Create the directory recursively if it doesn't exist
    if (is_dir($realFullPath)) {
        echo json_encode(['error' => 'Directory already exists']);
        exit;
    }

    if (mkdir($realFullPath, 0777, true)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'Failed to create directory']);
    }
    exit;
}

if ($_GET['action'] === 'delete') {
    $data = json_decode(file_get_contents('php://input'), true);
    $path = $data['path'];
    if (strpos(realpath($path), realpath('/home/projects')) !== 0) {
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }
    if (!file_exists($path)) {
        echo json_encode(['error' => 'File not found']);
        exit;
    }
    if (is_dir($path)) {
        rmdir($path);
    } else {
        unlink($path);
    }
    echo json_encode(['success' => true]);
    exit;
}

if ($_GET['action'] === 'rename') {
    $data = json_decode(file_get_contents('php://input'), true);
    $oldPath = $data['oldPath'];
    $newPath = $data['newPath'];

    // Security check: keep operations inside 'files' directory
    if (
        strpos(realpath($oldPath), realpath('/home/projects')) !== 0 ||
        strpos(realpath(dirname($newPath)), realpath('/home/projects')) !== 0
    ) {
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    if (!file_exists($oldPath)) {
        echo json_encode(['error' => 'File not found']);
        exit;
    }

    if (file_exists($newPath)) {
        echo json_encode(['error' => 'A file with the new name already exists']);
        exit;
    }

    if (!rename($oldPath, $newPath)) {
        echo json_encode(['error' => 'Rename failed']);
        exit;
    }

    echo json_encode(['success' => true]);
    exit;
}

if ($_GET['action'] === 'download') {
    if (!isset($_GET['path'])) {
        http_response_code(400);
        echo "Missing file path.";
        exit;
    }

    // Get the requested relative path (like "files/projects/readme.txt")
    $relativePath = $_GET['path'];

    // Map "files/projects" to the real system path
    $baseDir = realpath('/home/projects');  // the true directory on disk
    $requestedPath = realpath($baseDir . '/' . basename($relativePath));

    // Ensure the requested file is inside /home/projects
    if (!$requestedPath || strpos($requestedPath, $baseDir) !== 0 || !file_exists($requestedPath)) {
        http_response_code(403);
        echo "Unauthorized or file does not exist.";
        exit;
    }

    // Force download headers
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . basename($requestedPath) . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($requestedPath));
    readfile($requestedPath);
    exit;
}

if ($_GET['action'] === 'upload') {
    $baseDir = realpath('/home/projects');  // your actual base directory

    $uploadDir = $_POST['dir'] ?? '';
    $relativePath = preg_replace('#^files/projects/?#', '', ltrim($uploadDir, '/'));
    $targetDir = rtrim($baseDir . '/' . $relativePath, '/');

    // Auto-create the folder if it doesn't exist
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $realTarget = realpath($targetDir);

    // Security check: final resolved path must still be inside /home/projects
    if (!$realTarget || strpos($realTarget, $baseDir) !== 0) {
        echo json_encode(['error' => 'Invalid upload directory.']);
        exit;
    }

    if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['error' => 'Upload failed.']);
        exit;
    }

    $filename = basename($_FILES['file']['name']);
    $destination = $realTarget . '/' . $filename;

    if (!move_uploaded_file($_FILES['file']['tmp_name'], $destination)) {
        echo json_encode(['error' => 'Failed to save uploaded file.']);
        exit;
    }

    echo json_encode(['success' => true]);
    exit;
}

if ($action === 'realpath') {
    $dir = $_GET['dir'] ?? '.';
    $real = realpath($dir);
    echo json_encode(['path' => $real]);
    exit;
}

echo json_encode(['error' => 'Unknown action']);

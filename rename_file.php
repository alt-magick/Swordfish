<?php
$data = json_decode(file_get_contents('php://input'), true);
$oldPath = $data['oldPath'] ?? '';
$newName = $data['newName'] ?? '';

if(!$oldPath || !$newName){
    echo json_encode(['success' => false, 'error' => 'Invalid parameters']);
    exit;
}

$dir = dirname($oldPath);
$newPath = $dir === '.' ? $newName : $dir . '/' . $newName;

if(rename($oldPath, $newPath)){
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Could not rename file']);
}
?>

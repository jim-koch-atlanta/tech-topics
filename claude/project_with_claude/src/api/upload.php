<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\GifTransformer;

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

if (!isset($_FILES['gif']) || $_FILES['gif']['error'] !== UPLOAD_ERR_OK) {
    http_response_code(400);
    echo json_encode(['error' => 'No file uploaded or upload error']);
    exit;
}

$uploadedFile = $_FILES['gif'];
$tempDir = getenv('TMP_DIR') ?: '/var/www/html/tmp';

if (!is_dir($tempDir)) {
    mkdir($tempDir, 0755, true);
}

$tempPath = $tempDir . '/' . uniqid('upload_') . '.gif';

if (!move_uploaded_file($uploadedFile['tmp_name'], $tempPath)) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to save uploaded file']);
    exit;
}

if (!GifTransformer::validate($tempPath)) {
    unlink($tempPath);
    http_response_code(400);
    echo json_encode(['error' => 'Invalid GIF file']);
    exit;
}

$info = GifTransformer::getInfo($tempPath);

echo json_encode([
    'success' => true,
    'file' => basename($tempPath),
    'path' => $tempPath,
    'info' => $info,
]);

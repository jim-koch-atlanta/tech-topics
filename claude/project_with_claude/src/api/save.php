<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\S3Client;
use App\GifTransformer;

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['file'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing file parameter']);
    exit;
}

$tempDir = getenv('TMP_DIR') ?: '/var/www/html/tmp';
$filePath = $tempDir . '/' . basename($input['file']);

if (!file_exists($filePath)) {
    http_response_code(404);
    echo json_encode(['error' => 'File not found']);
    exit;
}

if (!GifTransformer::validate($filePath)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid GIF file']);
    exit;
}

$filename = $input['filename'] ?? ('gif_' . date('Y-m-d_H-i-s') . '.gif');
if (!str_ends_with(strtolower($filename), '.gif')) {
    $filename .= '.gif';
}

$s3 = new S3Client();
$success = $s3->upload($filePath, $filename);

if (!$success) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to save to S3']);
    exit;
}

unlink($filePath);

echo json_encode([
    'success' => true,
    'key' => $filename,
    'url' => $s3->getPublicUrl($filename),
]);

<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\S3Client;

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$s3 = new S3Client();
$files = $s3->list();

echo json_encode([
    'success' => true,
    'files' => $files,
]);

<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\GifTransformer;

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['file']) || !isset($input['transform'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing file or transform parameters']);
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

$transformer = new GifTransformer($filePath);
$transform = $input['transform'];
$outputPath = null;

switch ($transform['type']) {
    case 'resize':
        if (!isset($transform['width']) || !isset($transform['height'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing width or height for resize']);
            exit;
        }
        $keepAspectRatio = $transform['keepAspectRatio'] ?? true;
        $outputPath = $transformer->resize(
            (int) $transform['width'],
            (int) $transform['height'],
            $keepAspectRatio
        );
        break;

    case 'crop':
        if (!isset($transform['x']) || !isset($transform['y']) ||
            !isset($transform['width']) || !isset($transform['height'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing crop parameters (x, y, width, height)']);
            exit;
        }
        $outputPath = $transformer->crop(
            (int) $transform['x'],
            (int) $transform['y'],
            (int) $transform['width'],
            (int) $transform['height']
        );
        break;

    case 'rotate':
        if (!isset($transform['degrees'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing degrees for rotate']);
            exit;
        }
        $outputPath = $transformer->rotate((float) $transform['degrees']);
        break;

    case 'flip':
        if (!isset($transform['direction'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing direction for flip (horizontal or vertical)']);
            exit;
        }
        if (!in_array($transform['direction'], ['horizontal', 'vertical'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid flip direction']);
            exit;
        }
        $outputPath = $transformer->flip($transform['direction']);
        break;

    default:
        http_response_code(400);
        echo json_encode(['error' => 'Unknown transform type']);
        exit;
}

if ($outputPath === null) {
    http_response_code(500);
    echo json_encode(['error' => 'Transformation failed']);
    exit;
}

unlink($filePath);

$info = GifTransformer::getInfo($outputPath);

echo json_encode([
    'success' => true,
    'file' => basename($outputPath),
    'info' => $info,
]);

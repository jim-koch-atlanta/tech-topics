<?php
/**
 * Return current GIF preview
 */

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

// Start session
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Check if session exists
if (!isset($_SESSION['gif_current_path'])) {
    http_response_code(400);
    echo json_encode(['error' => 'No GIF loaded in session']);
    exit;
}

$gif_path = $_SESSION['gif_current_path'];

// Verify file exists
if (!file_exists($gif_path)) {
    http_response_code(404);
    echo json_encode(['error' => 'GIF file not found']);
    exit;
}

// Return GIF as data URI or base64
header('Content-Type: application/json');

$file_content = file_get_contents($gif_path);
$base64 = base64_encode($file_content);
$data_uri = 'data:image/gif;base64,' . $base64;

echo json_encode([
    'success' => true,
    'preview' => $data_uri,
    'filename' => $_SESSION['gif_filename'] ?? 'image.gif',
    'filesize' => filesize($gif_path),
    'transformations' => $_SESSION['gif_transformations'] ?? []
]);

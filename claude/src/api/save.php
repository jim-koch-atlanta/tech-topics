<?php
/**
 * Save the transformed GIF
 */

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

// Start session
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
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

$current_path = $_SESSION['gif_current_path'];
$filename = $_SESSION['gif_filename'] ?? 'transformed.gif';

// Verify file exists
if (!file_exists($current_path)) {
    http_response_code(404);
    echo json_encode(['error' => 'GIF file not found']);
    exit;
}

// Get file content for download
$file_content = file_get_contents($current_path);

// Return as download via JSON (frontend will handle actual download)
header('Content-Type: application/json');

$base64 = base64_encode($file_content);
$data_uri = 'data:image/gif;base64,' . $base64;

echo json_encode([
    'success' => true,
    'filename' => $filename,
    'download_url' => $data_uri,
    'filesize' => filesize($current_path),
    'transformations_applied' => count($_SESSION['gif_transformations'] ?? [])
]);

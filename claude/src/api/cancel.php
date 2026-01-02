<?php
/**
 * Cancel transformation session and cleanup
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

// Clean up session files
if (isset($_SESSION['gif_original_path']) && file_exists($_SESSION['gif_original_path'])) {
    @unlink($_SESSION['gif_original_path']);
}

if (isset($_SESSION['gif_current_path']) && file_exists($_SESSION['gif_current_path'])) {
    @unlink($_SESSION['gif_current_path']);
}

// Clear session
session_destroy();

header('Content-Type: application/json');
http_response_code(200);
echo json_encode([
    'success' => true,
    'message' => 'Session cancelled and files cleaned up'
]);

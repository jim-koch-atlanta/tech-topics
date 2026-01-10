<?php
/**
 * Apply ImageMagick transformations to GIF
 */

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

// Start session
session_start();

// Set up error handler to catch fatal errors
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    error_log("PHP ERROR [$errno]: $errstr in $errfile:$errline");
    if ($errno === E_FATAL) {
        http_response_code(500);
        echo json_encode(['error' => 'Fatal error: ' . $errstr]);
        exit;
    }
});

register_shutdown_function(function() {
    $error = error_get_last();
    if ($error !== null && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        error_log("SHUTDOWN ERROR: " . json_encode($error));
        http_response_code(500);
        echo json_encode(['error' => 'Fatal error: ' . $error['message']]);
    }
});

// Include the JimageMagick class
require_once __DIR__ . '/jimagick.php';

try {

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Check if session exists
if (!isset($_SESSION['gif_current_path'])) {
    http_response_code(400);
    echo json_encode(['error' => 'No GIF loaded in session']);
    error_log("ERROR: No gif_current_path in session. Session contents: " . json_encode($_SESSION));
    exit;
}

// Get transformation request
$input = json_decode(file_get_contents('php://input'), true);
if (!$input || !isset($input['action'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid transformation request']);
    exit;
}

$action = $input['action'];
$current_path = $_SESSION['gif_current_path'];
$transformed_dir = '/tmp/gif_uploads/transformed';  // Use /tmp instead of mounted volume

// Ensure transform directory exists
if (!is_dir($transformed_dir)) {
    mkdir($transformed_dir, 0777, true);
}

// Generate output path
$output_path = $transformed_dir . '/' . uniqid('transform_', true) . '.gif';

// Execute transformation using JimageMagick
error_log("DEBUG TRANSFORM: action=$action, current_path=$current_path, output_path=$output_path");

JimageMagick::execute($action, $current_path, $output_path, $input);

error_log("DEBUG: Transformation completed successfully");

// Verify output file exists and has size
if (!file_exists($output_path) || filesize($output_path) === 0) {
    http_response_code(500);
    echo json_encode(['error' => 'Transformation produced empty file']);
    exit;
}

// Update session with new path
$_SESSION['gif_current_path'] = $output_path;
$_SESSION['gif_transformations'][] = [
    'action' => $action,
    'params' => $input,
    'timestamp' => time()
];

http_response_code(200);
echo json_encode([
    'success' => true,
    'message' => ucfirst($action) . ' applied successfully'
]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Exception: ' . $e->getMessage(), 'file' => $e->getFile(), 'line' => $e->getLine()]);
    error_log("EXCEPTION in transform.php: " . $e->getMessage() . " at " . $e->getFile() . ":" . $e->getLine());
    exit;
}

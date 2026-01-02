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

/**
 * Build ImageMagick convert command
 */
function build_imagemagick_command($action, $input_path, $output_path, $params) {
    $input_path = escapeshellarg($input_path);
    $output_path = escapeshellarg($output_path);
    
    switch ($action) {
        case 'resize':
            $width = intval($params['width'] ?? 0);
            $height = intval($params['height'] ?? 0);
            if ($width <= 0 || $height <= 0) {
                return null;
            }
            return "convert {$input_path} -resize {$width}x{$height} {$output_path}";
        
        case 'crop':
            $width = intval($params['width'] ?? 0);
            $height = intval($params['height'] ?? 0);
            $x = intval($params['x'] ?? 0);
            $y = intval($params['y'] ?? 0);
            if ($width <= 0 || $height <= 0) {
                return null;
            }
            return "convert {$input_path} -crop {$width}x{$height}+{$x}+{$y} {$output_path}";
        
        case 'rotate':
            $angle = floatval($params['angle'] ?? 0);
            if ($angle == 0) {
                return null;
            }
            return "convert {$input_path} -rotate {$angle} {$output_path}";
        
        case 'flip':
            $direction = strtolower($params['direction'] ?? 'vertical');
            if ($direction === 'vertical') {
                return "convert {$input_path} -flip {$output_path}";
            } elseif ($direction === 'horizontal') {
                return "convert {$input_path} -flop {$output_path}";
            }
            return null;
        
        default:
            return null;
    }
}

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

// Build ImageMagick command
$command = build_imagemagick_command($action, $current_path, $output_path, $input);

if (!$command) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid transformation action']);
    exit;
}

error_log("DEBUG TRANSFORM: action=$action, current_path=$current_path, output_path=$output_path, command=$command");

// Execute transformation
$output = null;
$return_code = 0;
exec($command, $output, $return_code);

error_log("DEBUG: Command executed - return_code: $return_code, command: $command");
error_log("DEBUG: Output: " . implode("\n", $output));

if ($return_code !== 0) {
    http_response_code(500);
    echo json_encode(['error' => 'ImageMagick transformation failed', 'details' => implode("\n", $output), 'command' => $command]);
    exit;
}

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

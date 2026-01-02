<?php
/**
 * Handle GIF file upload
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

// Validate file upload
if (!isset($_FILES['gif']) || $_FILES['gif']['error'] !== UPLOAD_ERR_OK) {
    http_response_code(400);
    echo json_encode(['error' => 'File upload failed', 'file_error' => $_FILES['gif']['error'] ?? 'unknown']);
    exit;
}

$file = $_FILES['gif'];
$filename = $file['name'];
$tmp_path = $file['tmp_name'];
$file_size = $file['size'];

// FIXED: Use absolute paths - Use /tmp as fallback for Docker on Windows issues
$base_path = '/var/www/html';
$upload_dir = '/tmp/gif_uploads';  // Use /tmp instead of mounted volume
$html_upload_dir = $base_path . '/uploads/incoming';

// Ensure both directories exist
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}
if (!is_dir($html_upload_dir)) {
    mkdir($html_upload_dir, 0777, true);
}

error_log("DEBUG: Upload started - filename: $filename, tmp_path: $tmp_path, size: $file_size, base_path: $base_path, upload_dir: $upload_dir");

// Validate file type (check magic bytes for GIF)
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime_type = finfo_file($finfo, $tmp_path);
finfo_close($finfo);

error_log("DEBUG: MIME type: $mime_type");

if ($mime_type !== 'image/gif') {
    http_response_code(400);
    echo json_encode(['error' => 'Only GIF files are allowed', 'mime_type' => $mime_type]);
    exit;
}

// Validate file size (max 100MB as per php.ini)
if ($file_size > 100 * 1024 * 1024) {
    http_response_code(400);
    echo json_encode(['error' => 'File size exceeds 100MB limit']);
    exit;
}

// Generate unique session file ID
$session_id = uniqid('gif_', true);
$uploaded_path = $upload_dir . '/' . $session_id . '.gif';

// Ensure upload directory exists
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

error_log("DEBUG: Upload dir exists: " . (is_dir($upload_dir) ? 'yes' : 'no'));
error_log("DEBUG: Upload dir writable: " . (is_writable($upload_dir) ? 'yes' : 'no'));
error_log("DEBUG: Attempting to move to: $uploaded_path");

// Move uploaded file
if (!move_uploaded_file($tmp_path, $uploaded_path)) {
    http_response_code(500);
    $error_msg = 'Failed to save uploaded file';
    if (!is_writable($upload_dir)) {
        $error_msg .= ' - Upload directory not writable';
    }
    if (!file_exists($uploaded_path) && file_exists($tmp_path)) {
        $error_msg .= ' - Temp file still exists but move failed';
    }
    error_log("ERROR: $error_msg");
    echo json_encode(['error' => $error_msg, 'debug' => [
        'upload_dir' => $upload_dir,
        'is_writable' => is_writable($upload_dir),
        'tmp_exists' => file_exists($tmp_path),
        'user' => get_current_user(),
        'perms' => decoct(fileperms($upload_dir))
    ]]);
    exit;
}

error_log("DEBUG: File moved successfully to $uploaded_path");

// Store session data
$_SESSION['gif_session_id'] = $session_id;
$_SESSION['gif_filename'] = sanitize_filename($filename);
$_SESSION['gif_original_path'] = $uploaded_path;
$_SESSION['gif_current_path'] = $uploaded_path;
$_SESSION['gif_transformations'] = [];

http_response_code(200);
echo json_encode([
    'success' => true,
    'session_id' => $session_id,
    'filename' => $_SESSION['gif_filename']
]);

/**
 * Sanitize filename
 */
function sanitize_filename($filename) {
    return preg_replace('/[^a-zA-Z0-9._-]/', '_', $filename);
}


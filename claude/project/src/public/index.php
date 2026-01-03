<?php
/**
 * Main entry point for the GIF Transformation App
 */

// Start session
session_start();

// Set error handling
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

// Define base paths
define('APP_PATH', dirname(__FILE__));
define('BASE_PATH', dirname(APP_PATH));

// Set content type
header('Content-Type: text/html; charset=utf-8');

// Simple router
$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$request_method = $_SERVER['REQUEST_METHOD'];

error_log("DEBUG ROUTER: request_uri=$request_uri, method=$request_method");

// Health check endpoint
if ($request_uri === '/health') {
    http_response_code(200);
    echo json_encode(['status' => 'healthy']);
    exit;
}

// API endpoints
if (strpos($request_uri, '/api/') === 0) {
    header('Content-Type: application/json');
    
    switch ($request_uri) {
        case '/api/upload':
            require APP_PATH . '/api/upload.php';
            break;
        case '/api/transform':
            require APP_PATH . '/api/transform.php';
            break;
        case '/api/preview':
            require APP_PATH . '/api/preview.php';
            break;
        case '/api/save':
            require APP_PATH . '/api/save.php';
            break;
        case '/api/cancel':
            require APP_PATH . '/api/cancel.php';
            break;
        default:
            http_response_code(404);
            echo json_encode(['error' => 'Endpoint not found']);
            break;
    }
    exit;
}

// Serve main UI
require APP_PATH . '/views/index.html';

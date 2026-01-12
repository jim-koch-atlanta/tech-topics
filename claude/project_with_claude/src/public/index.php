<?php

$requestUri = $_SERVER['REQUEST_URI'];
$path = parse_url($requestUri, PHP_URL_PATH);

if (preg_match('#^/api/(.+)\.php$#', $path, $matches)) {
    $apiFile = __DIR__ . '/../api/' . $matches[1] . '.php';
    if (file_exists($apiFile)) {
        require $apiFile;
        exit;
    }
}

if (preg_match('#^/tmp/(.+\.gif)$#', $path, $matches)) {
    $tempDir = getenv('TMP_DIR') ?: '/var/www/html/tmp';
    $filePath = $tempDir . '/' . basename($matches[1]);
    if (file_exists($filePath)) {
        header('Content-Type: image/gif');
        header('Cache-Control: no-cache');
        readfile($filePath);
        exit;
    }
    http_response_code(404);
    exit;
}

if ($path === '/' || $path === '/index.php' || $path === '/index.html') {
    readfile(__DIR__ . '/../views/index.html');
    exit;
}

http_response_code(404);
echo 'Not Found';

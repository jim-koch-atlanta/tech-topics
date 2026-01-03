<?php
// Simple test endpoint
header('Content-Type: application/json');
session_start();
echo json_encode([
    'test' => 'OK',
    'session' => $_SESSION,
    'method' => $_SERVER['REQUEST_METHOD'],
    'post_data' => file_get_contents('php://input')
]);

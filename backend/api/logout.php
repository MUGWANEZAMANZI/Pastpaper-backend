<?php
// User Logout API
// Path: backend/api/logout.php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/Auth.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$auth = new Auth();
$result = $auth->logout();

echo json_encode($result);
?>

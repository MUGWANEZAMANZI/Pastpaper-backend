<?php
// Admin Panel API - User management
// Path: backend/api/admin-users.php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/Auth.php';
require_once __DIR__ . '/../includes/Database.php';

header('Content-Type: application/json');

$auth = new Auth();
$db = Database::getInstance();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Get all users
    $auth->requireAdmin();
    
    $users = $db->readJSON(USERS_FILE);
    
    // Enhance with reputation
    foreach ($users as &$user) {
        unset($user['password']);
        $reputation = $db->getUserReputation($user['id']);
        $user['reputation'] = $reputation;
    }
    
    echo json_encode(['success' => true, 'data' => $users]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Update user role or status
    $auth->requireAdmin();
    
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['user_id'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'User ID required']);
        exit;
    }
    
    $user = $db->getUserById($data['user_id']);
    if (!$user) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'User not found']);
        exit;
    }
    
    // Update user
    if (isset($data['role'])) {
        $user['role'] = $data['role'];
    }
    if (isset($data['status'])) {
        $user['status'] = $data['status'];
    }
    
    if ($db->saveUser($user)) {
        unset($user['password']);
        echo json_encode(['success' => true, 'message' => 'User updated successfully', 'data' => $user]);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Failed to update user']);
    }
    exit;
}

http_response_code(405);
echo json_encode(['success' => false, 'message' => 'Method not allowed']);
?>

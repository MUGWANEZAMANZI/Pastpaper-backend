<?php
// Answers API - Post or get answers
// Path: backend/api/answers.php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/Auth.php';
require_once __DIR__ . '/../includes/Database.php';

header('Content-Type: application/json');

$db = Database::getInstance();
$auth = new Auth();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Get answers for a question
    $questionId = $_GET['question_id'] ?? null;
    
    if (!$questionId) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Question ID required']);
        exit;
    }
    
    $answers = $db->getAnswersByQuestion($questionId);
    
    // Enhance with user info
    foreach ($answers as &$answer) {
        $user = $db->getUserById($answer['answered_by']);
        if ($user) {
            $answer['user_name'] = $user['name'];
            $answer['user_avatar'] = $user['avatar'];
        }
        $answer['is_current_user'] = isset($_SESSION['user_id']) && $_SESSION['user_id'] === $answer['answered_by'];
    }
    
    echo json_encode(['success' => true, 'data' => $answers]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Post new answer
    $auth->requireLogin();
    
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['question_id']) || !isset($data['content'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        exit;
    }
    
    $answer = [
        'question_id' => $data['question_id'],
        'content' => htmlspecialchars($data['content']),
        'answered_by' => $_SESSION['user_id'],
        'user_name' => $_SESSION['name'],
        'votes' => 0,
        'is_correct' => false
    ];
    
    if ($db->saveAnswer($answer)) {
        // Update reputation
        $db->updateReputation($_SESSION['user_id'], 10, 'answer');
        
        http_response_code(201);
        echo json_encode(['success' => true, 'message' => 'Answer posted successfully', 'data' => $answer]);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Failed to post answer']);
    }
    exit;
}

http_response_code(405);
echo json_encode(['success' => false, 'message' => 'Method not allowed']);
?>

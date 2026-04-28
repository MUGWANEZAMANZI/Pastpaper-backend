<?php
// Mark answer as correct
// Path: backend/api/mark-correct.php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/Auth.php';
require_once __DIR__ . '/../includes/Database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$auth = new Auth();
$auth->requireLogin();

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['answer_id']) || !isset($data['question_id'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Answer ID and Question ID required']);
    exit;
}

$db = Database::getInstance();

// Verify user is question author
$question = $db->getQuestionById($data['question_id']);
if ($question['posted_by'] !== $_SESSION['user_id']) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Only question author can mark answers as correct']);
    exit;
}

// Mark answer as correct
if ($db->markAnswerCorrect($data['answer_id'])) {
    // Award reputation
    $answers = $db->readJSON(ANSWERS_FILE);
    foreach ($answers as $answer) {
        if ($answer['id'] === $data['answer_id']) {
            $db->updateReputation($answer['answered_by'], 50, 'correct');
            break;
        }
    }
    
    echo json_encode(['success' => true, 'message' => 'Answer marked as correct']);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Failed to mark answer as correct']);
}
?>

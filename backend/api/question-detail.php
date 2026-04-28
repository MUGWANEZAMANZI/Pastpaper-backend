<?php
// Get single question by ID
// Path: backend/api/question-detail.php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/Database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$questionId = $_GET['id'] ?? null;

if (!$questionId) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Question ID required']);
    exit;
}

$db = Database::getInstance();
$question = $db->getQuestionById($questionId);

if (!$question) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Question not found']);
    exit;
}

$answers = $db->getAnswersByQuestion($questionId);

// Enhance answers with user info
foreach ($answers as &$answer) {
    $user = $db->getUserById($answer['answered_by']);
    if ($user) {
        $answer['user_name'] = $user['name'];
        $answer['user_avatar'] = $user['avatar'];
    }
}

$question['answers'] = $answers;
$question['answers_count'] = count($answers);

echo json_encode(['success' => true, 'data' => $question]);
?>

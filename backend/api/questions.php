<?php
// Questions API - Get all questions
// Path: backend/api/questions.php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/Auth.php';
require_once __DIR__ . '/../includes/Database.php';

header('Content-Type: application/json');

$db = Database::getInstance();
$auth = new Auth();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Get all questions
    $questions = $db->getAllQuestions();
    
    // Add answer count to each question
    foreach ($questions as &$question) {
        $answers = $db->getAnswersByQuestion($question['id']);
        $question['answers_count'] = count($answers);
        $question['top_answer'] = !empty($answers) ? $answers[0] : null;
    }
    
    echo json_encode(['success' => true, 'data' => $questions]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Admin only - Post new question
    $auth->requireAdmin();
    
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['title']) || !isset($data['questions'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        exit;
    }
    
    $question = [
        'title' => htmlspecialchars($data['title']),
        'description' => htmlspecialchars($data['description'] ?? ''),
        'questions' => $data['questions'], // JSON format questions
        'posted_by' => $_SESSION['user_id'],
        'posted_by_name' => $_SESSION['name'],
        'status' => 'active',
        'difficulty' => $data['difficulty'] ?? 'medium'
    ];
    
    if ($db->saveQuestion($question)) {
        http_response_code(201);
        echo json_encode(['success' => true, 'message' => 'Question posted successfully', 'data' => $question]);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Failed to post question']);
    }
    exit;
}

http_response_code(405);
echo json_encode(['success' => false, 'message' => 'Method not allowed']);
?>

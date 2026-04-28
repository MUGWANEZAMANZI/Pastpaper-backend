<?php
// Post new answer

$auth = new Auth();
$auth->requireLogin();

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['question_id']) || !isset($data['content'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

$db = Database::getInstance();

$answer = [
    'question_id' => $data['question_id'],
    'content' => htmlspecialchars($data['content']),
    'answered_by' => $_SESSION['user_id'],
    'user_name' => $_SESSION['name'],
    'votes' => 0,
    'is_correct' => false
];

if ($db->saveAnswer($answer)) {
    $db->updateReputation($_SESSION['user_id'], 10, 'answer');
    
    http_response_code(201);
    echo json_encode(['success' => true, 'message' => 'Answer posted successfully', 'data' => $answer]);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Failed to post answer']);
}
?>

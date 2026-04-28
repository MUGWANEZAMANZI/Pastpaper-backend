<?php
// Post new question handler (admin only)

$auth = new Auth();
$auth->requireAdmin();

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['title']) || !isset($data['questions'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

$db = Database::getInstance();

$question = [
    'title' => htmlspecialchars($data['title']),
    'description' => htmlspecialchars($data['description'] ?? ''),
    'questions' => $data['questions'],
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
?>

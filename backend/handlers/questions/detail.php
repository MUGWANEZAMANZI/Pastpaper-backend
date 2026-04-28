<?php
// Get question detail handler

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

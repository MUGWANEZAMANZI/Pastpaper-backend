<?php
// Get answers for question

$questionId = $_GET['question_id'] ?? null;

if (!$questionId) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Question ID required']);
    exit;
}

$db = Database::getInstance();
$answers = $db->getAnswersByQuestion($questionId);

foreach ($answers as &$answer) {
    $user = $db->getUserById($answer['answered_by']);
    if ($user) {
        $answer['user_name'] = $user['name'];
        $answer['user_avatar'] = $user['avatar'];
    }
    $answer['is_current_user'] = isset($_SESSION['user_id']) && $_SESSION['user_id'] === $answer['answered_by'];
}

echo json_encode(['success' => true, 'data' => $answers]);
?>

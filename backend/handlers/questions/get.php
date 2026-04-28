<?php
// Get all questions handler

$db = Database::getInstance();
$questions = $db->getAllQuestions();

foreach ($questions as &$question) {
    $answers = $db->getAnswersByQuestion($question['id']);
    $question['answers_count'] = count($answers);
    $question['top_answer'] = !empty($answers) ? $answers[0] : null;
}

echo json_encode(['success' => true, 'data' => $questions]);
?>

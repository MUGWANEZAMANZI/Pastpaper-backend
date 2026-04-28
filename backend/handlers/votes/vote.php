<?php
// Vote on answer

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$auth = new Auth();
$auth->requireLogin();

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['answer_id'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Answer ID required']);
    exit;
}

$db = Database::getInstance();
$answerId = $data['answer_id'];
$userId = $_SESSION['user_id'];

$votes = $db->readJSON(VOTES_FILE);
$alreadyVoted = false;

foreach ($votes as $vote) {
    if ($vote['answer_id'] === $answerId && $vote['user_id'] === $userId) {
        $alreadyVoted = true;
        break;
    }
}

if ($alreadyVoted) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'You have already voted on this answer']);
    exit;
}

$vote = [
    'answer_id' => $answerId,
    'user_id' => $userId,
    'vote_type' => 'upvote'
];

if ($db->saveVote($vote)) {
    $db->incrementAnswerVotes($answerId);
    
    $answers = $db->readJSON(ANSWERS_FILE);
    foreach ($answers as $answer) {
        if ($answer['id'] === $answerId) {
            $db->updateReputation($answer['answered_by'], 5, 'vote');
            break;
        }
    }
    
    echo json_encode(['success' => true, 'message' => 'Vote recorded successfully']);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Failed to record vote']);
}
?>

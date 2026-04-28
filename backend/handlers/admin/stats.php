<?php
// Admin statistics

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$auth = new Auth();
$auth->requireAdmin();

$db = Database::getInstance();

$users = $db->readJSON(USERS_FILE);
$questions = $db->getAllQuestions();
$answers = $db->readJSON(ANSWERS_FILE);
$votes = $db->readJSON(VOTES_FILE);

$stats = [
    'total_users' => count($users),
    'total_questions' => count($questions),
    'total_answers' => count($answers),
    'total_votes' => count($votes),
    'active_users' => countActiveUsers($users),
    'questions_by_difficulty' => groupByDifficulty($questions),
    'top_contributors' => $db->getLeaderboard(5)
];

echo json_encode(['success' => true, 'data' => $stats]);

function countActiveUsers($users) {
    $thirtyDaysAgo = strtotime('-30 days');
    $active = 0;
    foreach ($users as $user) {
        if (isset($user['last_login'])) {
            $lastLogin = strtotime($user['last_login']);
            if ($lastLogin > $thirtyDaysAgo) {
                $active++;
            }
        }
    }
    return $active;
}

function groupByDifficulty($questions) {
    $grouped = ['easy' => 0, 'medium' => 0, 'hard' => 0];
    foreach ($questions as $question) {
        $difficulty = $question['difficulty'] ?? 'medium';
        if (isset($grouped[$difficulty])) {
            $grouped[$difficulty]++;
        }
    }
    return $grouped;
}
?>

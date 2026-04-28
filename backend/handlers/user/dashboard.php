<?php
// User dashboard

$auth = new Auth();
$auth->requireLogin();

$db = Database::getInstance();
$userId = $_SESSION['user_id'];
$user = $db->getUserById($userId);
$reputation = $db->getUserReputation($userId);

$allAnswers = $db->readJSON(ANSWERS_FILE);
$userAnswers = [];
$correctAnswersCount = 0;
$totalVotes = 0;

foreach ($allAnswers as $answer) {
    if ($answer['answered_by'] === $userId) {
        $userAnswers[] = $answer;
        $totalVotes += $answer['votes'];
        if ($answer['is_correct']) {
            $correctAnswersCount++;
        }
    }
}

$allQuestions = $db->getAllQuestions();
$userQuestions = [];
foreach ($allQuestions as $question) {
    if ($question['posted_by'] === $userId) {
        $userQuestions[] = $question;
    }
}

$dashboardData = [
    'user' => [
        'id' => $user['id'],
        'name' => $user['name'],
        'email' => $user['email'],
        'avatar' => $user['avatar'],
        'role' => $user['role']
    ],
    'reputation' => [
        'total_points' => $reputation['reputation_points'],
        'answers_count' => count($userAnswers),
        'correct_answers' => $correctAnswersCount,
        'total_votes' => $totalVotes
    ],
    'activity' => [
        'answered_questions' => count($userAnswers),
        'posted_questions' => count($userQuestions),
        'badges' => generateBadges($reputation, $correctAnswersCount)
    ],
    'recent_answers' => array_slice($userAnswers, -5),
    'recent_questions' => array_slice($userQuestions, -5)
];

echo json_encode(['success' => true, 'data' => $dashboardData]);

function generateBadges($reputation, $correctAnswers) {
    $badges = [];
    
    if ($reputation['reputation_points'] >= 100) {
        $badges[] = ['name' => 'Rising Star', 'icon' => '⭐'];
    }
    if ($reputation['reputation_points'] >= 500) {
        $badges[] = ['name' => 'Expert', 'icon' => '🏆'];
    }
    if ($correctAnswers >= 5) {
        $badges[] = ['name' => 'Correct Answers', 'icon' => '✅'];
    }
    if ($reputation['answers_count'] >= 20) {
        $badges[] = ['name' => 'Helpful', 'icon' => '🤝'];
    }
    
    return $badges;
}
?>

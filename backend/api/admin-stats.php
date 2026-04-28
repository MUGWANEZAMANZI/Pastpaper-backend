<?php
// Admin Panel API - Statistics and Analytics
// Path: backend/api/admin-stats.php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/Auth.php';
require_once __DIR__ . '/../includes/Database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$auth = new Auth();
$auth->requireAdmin();

$db = Database::getInstance();

// Gather statistics
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
    'most_voted_answers' => array_slice(
        usort(($answers ?: []), fn($a, $b) => $b['votes'] <=> $a['votes']) ?? $answers,
        0,
        10
    ),
    'recent_activity' => getRecentActivity($questions, $answers, $votes),
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

function getRecentActivity($questions, $answers, $votes) {
    $activity = [];
    
    // Recent questions
    if (is_array($questions)) {
        $recent = array_slice($questions, -3);
        foreach ($recent as $q) {
            $activity[] = [
                'type' => 'question',
                'title' => $q['title'],
                'timestamp' => $q['created_at'] ?? date('Y-m-d H:i:s')
            ];
        }
    }
    
    return $activity;
}
?>

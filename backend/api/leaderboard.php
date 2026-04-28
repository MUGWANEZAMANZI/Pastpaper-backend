<?php
// Leaderboard API
// Path: backend/api/leaderboard.php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/Database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$db = Database::getInstance();
$limit = $_GET['limit'] ?? 20;
$page = $_GET['page'] ?? 1;

$leaderboard = $db->getLeaderboard(1000);

// Get user details for leaderboard
$enhancedLeaderboard = [];
foreach ($leaderboard as $entry) {
    $user = $db->getUserById($entry['user_id']);
    if ($user) {
        $enhancedLeaderboard[] = [
            'rank' => count($enhancedLeaderboard) + 1,
            'user_name' => $user['name'],
            'user_id' => $user['id'],
            'avatar' => $user['avatar'],
            'reputation_points' => $entry['reputation_points'],
            'answers_count' => $entry['answers_count'] ?? 0,
            'correct_answers' => $entry['correct_answers'] ?? 0
        ];
    }
}

// Paginate
$total = count($enhancedLeaderboard);
$start = ($page - 1) * $limit;
$paginatedLeaderboard = array_slice($enhancedLeaderboard, $start, $limit);

echo json_encode([
    'success' => true,
    'data' => $paginatedLeaderboard,
    'pagination' => [
        'current_page' => $page,
        'per_page' => $limit,
        'total' => $total,
        'last_page' => ceil($total / $limit)
    ]
]);
?>

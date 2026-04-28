<?php
// Get leaderboard

$limit = $_GET['limit'] ?? 20;
$page = $_GET['page'] ?? 1;

$db = Database::getInstance();
$leaderboard = $db->getLeaderboard(1000);

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

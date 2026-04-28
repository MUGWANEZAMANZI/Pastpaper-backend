<?php
// Main Router - index.php
// Path: backend/index.php
// Handles all API requests and routes to appropriate handlers

require_once __DIR__ . '/config.php';

// Get the request method and path
$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Remove base path to get the endpoint
$basePath = '/Pastpapers-database/backend';
$endpoint = str_replace($basePath, '', $path);
$endpoint = trim($endpoint, '/');

// Set JSON response header
header('Content-Type: application/json');

// Route the request
try {
    switch ($endpoint) {
        // Authentication endpoints
        case 'register':
            if ($method === 'POST') {
                require_once __DIR__ . '/includes/Auth.php';
                require_once __DIR__ . '/handlers/auth/register.php';
                exit;
            }
            break;
            
        case 'login':
            if ($method === 'POST') {
                require_once __DIR__ . '/includes/Auth.php';
                require_once __DIR__ . '/handlers/auth/login.php';
                exit;
            }
            break;
            
        case 'logout':
            if ($method === 'POST') {
                require_once __DIR__ . '/includes/Auth.php';
                require_once __DIR__ . '/handlers/auth/logout.php';
                exit;
            }
            break;
            
        // Question endpoints
        case 'questions':
            require_once __DIR__ . '/includes/Auth.php';
            require_once __DIR__ . '/includes/Database.php';
            
            if ($method === 'GET') {
                require_once __DIR__ . '/handlers/questions/get.php';
            } elseif ($method === 'POST') {
                require_once __DIR__ . '/handlers/questions/post.php';
            }
            exit;
            
        case (preg_match('/^questions\/(\d+)$/', $endpoint, $matches) ? true : false):
            require_once __DIR__ . '/includes/Database.php';
            
            if ($method === 'GET') {
                $_GET['id'] = $matches[1];
                require_once __DIR__ . '/handlers/questions/detail.php';
            }
            exit;
            
        // Answer endpoints
        case 'answers':
            require_once __DIR__ . '/includes/Auth.php';
            require_once __DIR__ . '/includes/Database.php';
            
            if ($method === 'GET') {
                require_once __DIR__ . '/handlers/answers/get.php';
            } elseif ($method === 'POST') {
                require_once __DIR__ . '/handlers/answers/post.php';
            }
            exit;
            
        case 'vote':
            require_once __DIR__ . '/includes/Auth.php';
            require_once __DIR__ . '/includes/Database.php';
            
            if ($method === 'POST') {
                require_once __DIR__ . '/handlers/votes/vote.php';
            }
            exit;
            
        case 'mark-correct':
            require_once __DIR__ . '/includes/Auth.php';
            require_once __DIR__ . '/includes/Database.php';
            
            if ($method === 'POST') {
                require_once __DIR__ . '/handlers/answers/mark-correct.php';
            }
            exit;
            
        // User endpoints
        case 'dashboard':
            require_once __DIR__ . '/includes/Auth.php';
            require_once __DIR__ . '/includes/Database.php';
            
            if ($method === 'GET') {
                require_once __DIR__ . '/handlers/user/dashboard.php';
            }
            exit;
            
        case 'leaderboard':
            require_once __DIR__ . '/includes/Database.php';
            
            if ($method === 'GET') {
                require_once __DIR__ . '/handlers/leaderboard/get.php';
            }
            exit;
            
        // Admin endpoints
        case 'admin/users':
            require_once __DIR__ . '/includes/Auth.php';
            require_once __DIR__ . '/includes/Database.php';
            
            if ($method === 'GET' || $method === 'PUT') {
                require_once __DIR__ . '/handlers/admin/users.php';
            }
            exit;
            
        case 'admin/stats':
            require_once __DIR__ . '/includes/Auth.php';
            require_once __DIR__ . '/includes/Database.php';
            
            if ($method === 'GET') {
                require_once __DIR__ . '/handlers/admin/stats.php';
            }
            exit;
            
        default:
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'message' => 'Endpoint not found: ' . htmlspecialchars($endpoint)
            ]);
            exit;
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Server error: ' . $e->getMessage()
    ]);
    exit;
}

http_response_code(405);
echo json_encode(['success' => false, 'message' => 'Method not allowed']);
?>

<?php
// Configuration file for Pastpapers Database Backend
// Path: backend/config.php

// Set timezone
date_default_timezone_set('UTC');

// Security headers
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');

// API Configuration
define('API_BASE_URL', $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/Pastpapers%20database/backend');
define('DATA_DIR', __DIR__ . '/data/');

// Session Configuration
define('SESSION_TIMEOUT', 3600); // 1 hour
define('ADMIN_ROLE', 'admin');
define('USER_ROLE', 'user');

// CORS Settings (configure for your domain)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

// File paths
define('USERS_FILE', DATA_DIR . 'users.json');
define('QUESTIONS_FILE', DATA_DIR . 'questions.json');
define('ANSWERS_FILE', DATA_DIR . 'answers.json');
define('VOTES_FILE', DATA_DIR . 'votes.json');
define('REPUTATION_FILE', DATA_DIR . 'reputation.json');

// Initialize session
session_start();

// Set session timeout
if (isset($_SESSION['user_id']) && isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > SESSION_TIMEOUT)) {
    session_destroy();
    $_SESSION = array();
}

if (isset($_SESSION['user_id'])) {
    $_SESSION['last_activity'] = time();
}
?>

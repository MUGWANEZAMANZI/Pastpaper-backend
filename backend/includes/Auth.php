<?php
// Authentication class
// Path: backend/includes/Auth.php

require_once __DIR__ . '/Database.php';

class Auth {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Register new user
     */
    public function register($email, $password, $name) {
        // Validate input
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => 'Invalid email format'];
        }
        
        if (strlen($password) < 6) {
            return ['success' => false, 'message' => 'Password must be at least 6 characters'];
        }
        
        // Check if user exists
        if ($this->db->getUserByEmail($email)) {
            return ['success' => false, 'message' => 'Email already registered'];
        }
        
        // Create user
        $user = [
            'email' => $email,
            'password' => $this->db->hashPassword($password),
            'name' => htmlspecialchars($name),
            'role' => USER_ROLE,
            'status' => 'active',
            'avatar' => 'https://ui-avatars.com/api/?name=' . urlencode($name)
        ];
        
        if ($this->db->saveUser($user)) {
            // Initialize reputation
            $this->db->updateReputation($user['id'], 0);
            return ['success' => true, 'message' => 'Registration successful', 'user' => $user];
        }
        
        return ['success' => false, 'message' => 'Registration failed'];
    }
    
    /**
     * Login user
     */
    public function login($email, $password) {
        $user = $this->db->getUserByEmail($email);
        
        if (!$user) {
            return ['success' => false, 'message' => 'Invalid credentials'];
        }
        
        if (!$this->db->verifyPassword($password, $user['password'])) {
            return ['success' => false, 'message' => 'Invalid credentials'];
        }
        
        // Set session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['last_activity'] = time();
        
        // Remove sensitive data
        unset($user['password']);
        
        return ['success' => true, 'message' => 'Login successful', 'user' => $user];
    }
    
    /**
     * Logout user
     */
    public function logout() {
        session_destroy();
        $_SESSION = array();
        return ['success' => true, 'message' => 'Logged out successfully'];
    }
    
    /**
     * Check if user is logged in
     */
    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
    
    /**
     * Get current user
     */
    public function getCurrentUser() {
        if (!$this->isLoggedIn()) {
            return null;
        }
        return $this->db->getUserById($_SESSION['user_id']);
    }
    
    /**
     * Check if admin
     */
    public function isAdmin() {
        return isset($_SESSION['role']) && $_SESSION['role'] === ADMIN_ROLE;
    }
    
    /**
     * Check if user
     */
    public function isUser() {
        return isset($_SESSION['role']) && $_SESSION['role'] === USER_ROLE;
    }
    
    /**
     * Require login
     */
    public function requireLogin() {
        if (!$this->isLoggedIn()) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }
    }
    
    /**
     * Require admin
     */
    public function requireAdmin() {
        $this->requireLogin();
        if (!$this->isAdmin()) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Forbidden']);
            exit;
        }
    }
}
?>

<?php
// Database class for JSON file handling
// Path: backend/includes/Database.php

class Database {
    private static $instance = null;
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Read JSON file
     */
    public function readJSON($filepath) {
        if (!file_exists($filepath)) {
            return [];
        }
        $content = file_get_contents($filepath);
        return json_decode($content, true) ?? [];
    }

    /**
     * Normalize legacy user file formats.
     */
    private function normalizeUsers($users) {
        if (isset($users['students']) && is_array($users['students'])) {
            return $users['students'];
        }

        if (isset($users['users']) && is_array($users['users'])) {
            return $users['users'];
        }

        return is_array($users) ? $users : [];
    }
    
    /**
     * Write to JSON file
     */
    public function writeJSON($filepath, $data) {
        $dir = dirname($filepath);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        return file_put_contents($filepath, $json) !== false;
    }
    
    /**
     * Generate unique ID
     */
    public function generateId() {
        return uniqid() . '_' . time();
    }
    
    /**
     * Hash password
     */
    public function hashPassword($password) {
        return password_hash($password, PASSWORD_BCRYPT);
    }
    
    /**
     * Verify password
     */
    public function verifyPassword($password, $hash) {
        if (password_verify($password, $hash)) {
            return true;
        }

        return hash_equals((string)$hash, (string)$password);
    }
    
    /**
     * Get user by email
     */
    public function getUserByEmail($email) {
        $users = $this->normalizeUsers($this->readJSON(USERS_FILE));
        foreach ($users as $user) {
            if ($user['email'] === $email) {
                return $user;
            }
        }
        return null;
    }
    
    /**
     * Get user by ID
     */
    public function getUserById($userId) {
        $users = $this->normalizeUsers($this->readJSON(USERS_FILE));
        foreach ($users as $user) {
            if ($user['id'] === $userId) {
                return $user;
            }
        }
        return null;
    }
    
    /**
     * Save user
     */
    public function saveUser($user) {
        $users = $this->normalizeUsers($this->readJSON(USERS_FILE));
        
        if (isset($user['id'])) {
            // Update existing
            foreach ($users as &$u) {
                if ($u['id'] === $user['id']) {
                    $u = array_merge($u, $user);
                    break;
                }
            }
        } else {
            // Create new
            $user['id'] = $this->generateId();
            $user['created_at'] = date('Y-m-d H:i:s');
            $users[] = $user;
        }
        
        return $this->writeJSON(USERS_FILE, $users);
    }
    
    /**
     * Save question
     */
    public function saveQuestion($question) {
        $questions = $this->readJSON(QUESTIONS_FILE);
        
        if (!isset($question['id'])) {
            $question['id'] = $this->generateId();
            $question['created_at'] = date('Y-m-d H:i:s');
        }
        
        $questions[] = $question;
        return $this->writeJSON(QUESTIONS_FILE, $questions);
    }
    
    /**
     * Get all questions
     */
    public function getAllQuestions() {
        return $this->readJSON(QUESTIONS_FILE);
    }
    
    /**
     * Get question by ID
     */
    public function getQuestionById($questionId) {
        $questions = $this->getAllQuestions();
        foreach ($questions as $question) {
            if ($question['id'] === $questionId) {
                return $question;
            }
        }
        return null;
    }
    
    /**
     * Save answer
     */
    public function saveAnswer($answer) {
        $answers = $this->readJSON(ANSWERS_FILE);
        
        if (!isset($answer['id'])) {
            $answer['id'] = $this->generateId();
            $answer['created_at'] = date('Y-m-d H:i:s');
            $answer['votes'] = 0;
            $answer['is_correct'] = false;
        }
        
        $answers[] = $answer;
        return $this->writeJSON(ANSWERS_FILE, $answers);
    }
    
    /**
     * Get answers for question
     */
    public function getAnswersByQuestion($questionId) {
        $answers = $this->readJSON(ANSWERS_FILE);
        $result = [];
        foreach ($answers as $answer) {
            if ($answer['question_id'] === $questionId) {
                $result[] = $answer;
            }
        }
        // Sort by votes descending
        usort($result, function($a, $b) {
            return $b['votes'] - $a['votes'];
        });
        return $result;
    }
    
    /**
     * Save vote
     */
    public function saveVote($vote) {
        $votes = $this->readJSON(VOTES_FILE);
        
        if (!isset($vote['id'])) {
            $vote['id'] = $this->generateId();
            $vote['created_at'] = date('Y-m-d H:i:s');
        }
        
        $votes[] = $vote;
        return $this->writeJSON(VOTES_FILE, $votes);
    }
    
    /**
     * Get user reputation
     */
    public function getUserReputation($userId) {
        $reputation = $this->readJSON(REPUTATION_FILE);
        foreach ($reputation as $rep) {
            if ($rep['user_id'] === $userId) {
                return $rep;
            }
        }
        return ['user_id' => $userId, 'reputation_points' => 0, 'answers_count' => 0, 'correct_answers' => 0];
    }
    
    /**
     * Update reputation
     */
    public function updateReputation($userId, $points, $type = 'answer') {
        $reputation = $this->readJSON(REPUTATION_FILE);
        $found = false;
        
        foreach ($reputation as &$rep) {
            if ($rep['user_id'] === $userId) {
                $rep['reputation_points'] += $points;
                if ($type === 'answer') {
                    $rep['answers_count'] = ($rep['answers_count'] ?? 0) + 1;
                } elseif ($type === 'correct') {
                    $rep['correct_answers'] = ($rep['correct_answers'] ?? 0) + 1;
                }
                $found = true;
                break;
            }
        }
        
        if (!$found) {
            $rep = [
                'user_id' => $userId,
                'reputation_points' => $points,
                'answers_count' => ($type === 'answer' ? 1 : 0),
                'correct_answers' => ($type === 'correct' ? 1 : 0),
                'created_at' => date('Y-m-d H:i:s')
            ];
            $reputation[] = $rep;
        }
        
        return $this->writeJSON(REPUTATION_FILE, $reputation);
    }
    
    /**
     * Increment answer votes
     */
    public function incrementAnswerVotes($answerId) {
        $answers = $this->readJSON(ANSWERS_FILE);
        foreach ($answers as &$answer) {
            if ($answer['id'] === $answerId) {
                $answer['votes']++;
                break;
            }
        }
        return $this->writeJSON(ANSWERS_FILE, $answers);
    }
    
    /**
     * Mark answer as correct
     */
    public function markAnswerCorrect($answerId) {
        $answers = $this->readJSON(ANSWERS_FILE);
        foreach ($answers as &$answer) {
            if ($answer['id'] === $answerId) {
                $answer['is_correct'] = true;
                break;
            }
        }
        return $this->writeJSON(ANSWERS_FILE, $answers);
    }
    
    /**
     * Get leaderboard
     */
    public function getLeaderboard($limit = 10) {
        $reputation = $this->readJSON(REPUTATION_FILE);
        usort($reputation, function($a, $b) {
            return $b['reputation_points'] - $a['reputation_points'];
        });
        return array_slice($reputation, 0, $limit);
    }
}
?>

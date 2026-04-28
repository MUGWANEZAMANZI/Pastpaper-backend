<?php
// Initialize sample data
// Path: backend/init-data.php

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/Database.php';

$db = Database::getInstance();

// Create sample users
$users = [
    [
        'id' => 'admin_001',
        'name' => 'Admin User',
        'email' => 'admin@pastpapers.com',
        'password' => '$2y$10$1234567890abcdefghijkl', // bcrypt hash of 'admin123'
        'role' => 'admin',
        'status' => 'active',
        'avatar' => 'https://ui-avatars.com/api/?name=Admin+User',
        'created_at' => date('Y-m-d H:i:s')
    ],
    [
        'id' => 'user_001',
        'name' => 'John Doe',
        'email' => 'john@school.com',
        'password' => '$2y$10$abcdefghijklmnopqrstuv', // bcrypt hash of 'user123'
        'role' => 'user',
        'status' => 'active',
        'avatar' => 'https://ui-avatars.com/api/?name=John+Doe',
        'created_at' => date('Y-m-d H:i:s', strtotime('-30 days'))
    ],
    [
        'id' => 'user_002',
        'name' => 'Jane Smith',
        'email' => 'jane@school.com',
        'password' => '$2y$10$wxyzabcdefghijklmnopqr', // bcrypt hash of 'user123'
        'role' => 'user',
        'status' => 'active',
        'avatar' => 'https://ui-avatars.com/api/?name=Jane+Smith',
        'created_at' => date('Y-m-d H:i:s', strtotime('-20 days'))
    ]
];

$db->writeJSON(USERS_FILE, $users);

// Create sample questions in JSON format
$questions = [
    [
        'id' => 'q_001',
        'title' => 'Computer Fundamentals - 2023 Paper 1',
        'description' => 'Questions from Computer Science past paper',
        'posted_by' => 'admin_001',
        'posted_by_name' => 'Admin User',
        'difficulty' => 'medium',
        'status' => 'active',
        'created_at' => date('Y-m-d H:i:s', strtotime('-20 days')),
        'questions' => [
            [
                'question_number' => 1,
                'question_text' => 'Define what is meant by the term "variable"',
                'marks' => 2
            ],
            [
                'question_number' => 2,
                'question_text' => 'State three advantages of using a high-level programming language',
                'marks' => 3
            ],
            [
                'question_number' => 3,
                'question_text' => 'Describe the difference between RAM and ROM',
                'marks' => 4
            ]
        ]
    ],
    [
        'id' => 'q_002',
        'title' => 'Data Structures - 2023 Paper 2',
        'description' => 'Questions about arrays, lists and trees',
        'posted_by' => 'admin_001',
        'posted_by_name' => 'Admin User',
        'difficulty' => 'hard',
        'status' => 'active',
        'created_at' => date('Y-m-d H:i:s', strtotime('-15 days')),
        'questions' => [
            [
                'question_number' => 1,
                'question_text' => 'Explain what is meant by a "binary tree"',
                'marks' => 3
            ],
            [
                'question_number' => 2,
                'question_text' => 'Write pseudocode to search for an element in a binary search tree',
                'marks' => 5
            ]
        ]
    ],
    [
        'id' => 'q_003',
        'title' => 'Database Management - 2022 Paper 1',
        'description' => 'SQL and database design questions',
        'posted_by' => 'admin_001',
        'posted_by_name' => 'Admin User',
        'difficulty' => 'easy',
        'status' => 'active',
        'created_at' => date('Y-m-d H:i:s', strtotime('-10 days')),
        'questions' => [
            [
                'question_number' => 1,
                'question_text' => 'What does SQL stand for?',
                'marks' => 1
            ],
            [
                'question_number' => 2,
                'question_text' => 'Write a SQL query to retrieve all students with a grade greater than 80',
                'marks' => 3
            ]
        ]
    ]
];

$db->writeJSON(QUESTIONS_FILE, $questions);

// Create sample answers
$answers = [
    [
        'id' => 'ans_001',
        'question_id' => 'q_001',
        'content' => 'A variable is a named location in memory that stores a value. It has a data type, name, and value that can change during program execution.',
        'answered_by' => 'user_001',
        'votes' => 5,
        'is_correct' => true,
        'created_at' => date('Y-m-d H:i:s', strtotime('-18 days'))
    ],
    [
        'id' => 'ans_002',
        'question_id' => 'q_001',
        'content' => 'Variables are containers for storing data values in programming.',
        'answered_by' => 'user_002',
        'votes' => 2,
        'is_correct' => false,
        'created_at' => date('Y-m-d H:i:s', strtotime('-17 days'))
    ],
    [
        'id' => 'ans_003',
        'question_id' => 'q_003',
        'content' => 'SELECT * FROM students WHERE grade > 80;',
        'answered_by' => 'user_001',
        'votes' => 8,
        'is_correct' => true,
        'created_at' => date('Y-m-d H:i:s', strtotime('-8 days'))
    ]
];

$db->writeJSON(ANSWERS_FILE, $answers);

// Create sample votes
$votes = [
    [
        'id' => 'v_001',
        'answer_id' => 'ans_001',
        'user_id' => 'user_002',
        'vote_type' => 'upvote',
        'created_at' => date('Y-m-d H:i:s', strtotime('-17 days'))
    ],
    [
        'id' => 'v_002',
        'answer_id' => 'ans_001',
        'user_id' => 'user_003',
        'vote_type' => 'upvote',
        'created_at' => date('Y-m-d H:i:s', strtotime('-16 days'))
    ]
];

$db->writeJSON(VOTES_FILE, $votes);

// Create reputation data
$reputation = [
    [
        'user_id' => 'user_001',
        'reputation_points' => 85,
        'answers_count' => 2,
        'correct_answers' => 2,
        'created_at' => date('Y-m-d H:i:s', strtotime('-30 days'))
    ],
    [
        'user_id' => 'user_002',
        'reputation_points' => 15,
        'answers_count' => 1,
        'correct_answers' => 0,
        'created_at' => date('Y-m-d H:i:s', strtotime('-20 days'))
    ]
];

$db->writeJSON(REPUTATION_FILE, $reputation);

echo "Sample data initialized successfully!";
?>

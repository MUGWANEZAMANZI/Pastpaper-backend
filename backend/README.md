# Pastpapers Database - PHP Backend
## Complete Learning Management System

A comprehensive backend system for managing past papers, enabling students to answer questions, earn reputation points, and compete on leaderboards.

---

## Features

### 👥 User Management
- User registration and authentication
- Secure password hashing with bcrypt
- Session management
- User profiles with avatars
- Role-based access (Admin, User)

### 📝 Question Management
- Admin-only question posting
- Questions stored in JSON format
- Difficulty levels (Easy, Medium, Hard)
- Multiple questions per paper set

### 💬 Answer System
- Users can answer posted questions
- Answers sorted by vote count
- Automatic reputation tracking
- Search and filter answers

### 🗳️ Voting System
- Upvote answers
- One vote per user per answer
- Automatic reputation awards for voted answers
- Most voted answer marked as correct

### 🏆 Reputation & Leaderboard
- Dynamic reputation points
- Points for posting answers (+10)
- Points for receiving votes (+5 per vote)
- Points for correct answers (+50)
- Global leaderboard with rankings
- User badges (Rising Star, Expert, Helpful)

### 📊 Admin Dashboard
- View platform statistics
- User management
- Question management
- Analytics and reports
- Moderation tools

### 👤 User Dashboard
- Personal profile with avatar
- My answers tracking
- Reputation summary
- Badges earned
- Recent activity

---

## API Endpoints

### Authentication
```
POST /api/register.php          - Register new user
POST /api/login.php             - Login user
POST /api/logout.php            - Logout user
```

### Questions
```
GET  /api/questions.php         - Get all questions
POST /api/questions.php         - Post new question (Admin only)
GET  /api/question-detail.php   - Get question with answers
```

### Answers
```
GET  /api/answers.php           - Get answers for question
POST /api/answers.php           - Post new answer
POST /api/vote.php              - Vote on answer
POST /api/mark-correct.php      - Mark answer as correct (Admin/Question author)
```

### User
```
GET  /api/dashboard.php         - Get user dashboard data
GET  /api/leaderboard.php       - Get top users/leaderboard
```

### Admin
```
GET  /api/admin-users.php       - Get all users
PUT  /api/admin-users.php       - Update user role/status
GET  /api/admin-stats.php       - Get platform statistics
```

---

## Installation on Hostinger

### Step 1: Prepare Your Account
1. Log in to your Hostinger control panel
2. Go to **File Manager**
3. Navigate to your public_html directory

### Step 2: Upload Files
1. Create a new folder: `Pastpapers database/backend`
2. Upload the entire backend folder structure:
   ```
   backend/
   ├── config.php
   ├── init-data.php
   ├── includes/
   │   ├── Database.php
   │   └── Auth.php
   ├── api/
   │   ├── register.php
   │   ├── login.php
   │   ├── questions.php
   │   ├── answers.php
   │   ├── vote.php
   │   ├── dashboard.php
   │   ├── leaderboard.php
   │   ├── admin-users.php
   │   └── admin-stats.php
   ├── admin/
   │   └── index.html
   └── data/
       └── (empty - will be created automatically)
   ```

### Step 3: Set Permissions
1. Right-click on the `backend/data` folder
2. Select **Change Permissions**
3. Set permissions to `755` (read/write/execute for owner)
4. Check "Recursive" to apply to all files

### Step 4: Initialize Data
1. Open your browser and go to: `https://yourdomain.com/Pastpapers%20database/backend/init-data.php`
2. You should see: "Sample data initialized successfully!"
3. Check that folders were created with data files

### Step 5: Test the System

**Create account:**
```bash
curl -X POST "https://yourdomain.com/Pastpapers%20database/backend/api/register.php" \
  -H "Content-Type: application/json" \
  -d '{"email":"test@example.com","password":"test123","name":"Test User"}'
```

**Login:**
```bash
curl -X POST "https://yourdomain.com/Pastpapers%20database/backend/api/login.php" \
  -H "Content-Type: application/json" \
  -d '{"email":"test@example.com","password":"test123"}'
```

**Get questions:**
```bash
curl "https://yourdomain.com/Pastpapers%20database/backend/api/questions.php"
```

---

## File Structure Explained

### config.php
Central configuration file with:
- File paths
- Session settings
- API base URL
- CORS headers

### includes/Database.php
JSON-based database handler with methods for:
- Reading/writing JSON files
- User management
- Question/Answer CRUD
- Voting system
- Reputation tracking

### includes/Auth.php
Authentication class handling:
- User registration validation
- Login verification
- Session management
- Permission checking

### api/*.php
Individual API endpoint handlers that:
- Validate requests
- Call Database and Auth classes
- Return JSON responses
- Handle errors appropriately

### admin/index.html
Admin dashboard with:
- Statistics display
- Question posting form
- User management table
- Analytics charts
- Real-time data updates

---

## Data Format

### User Object
```json
{
  "id": "user_001",
  "name": "John Doe",
  "email": "john@example.com",
  "password": "$2y$10$hashedpassword",
  "role": "user",
  "status": "active",
  "avatar": "https://ui-avatars.com/api/?name=John+Doe",
  "created_at": "2024-01-15 10:30:00"
}
```

### Question Object
```json
{
  "id": "q_001",
  "title": "Computer Science - 2023 Paper 1",
  "description": "Questions from computer science exam",
  "posted_by": "admin_001",
  "posted_by_name": "Admin User",
  "difficulty": "medium",
  "questions": [
    {
      "question_number": 1,
      "question_text": "What is a variable?",
      "marks": 2
    }
  ],
  "created_at": "2024-01-15 10:30:00"
}
```

### Answer Object
```json
{
  "id": "ans_001",
  "question_id": "q_001",
  "content": "A variable is a named memory location...",
  "answered_by": "user_001",
  "votes": 5,
  "is_correct": true,
  "created_at": "2024-01-16 11:00:00"
}
```

---

## Security Considerations

✅ **Implemented:**
- Password hashing with bcrypt
- Session-based authentication
- User permission verification
- Input sanitization with htmlspecialchars()
- JSON validation
- CORS headers

**Recommendations:**
- Use HTTPS only
- Implement rate limiting
- Add CSRF tokens for sensitive operations
- Regular security audits
- Backup data regularly
- Monitor file permissions

---

## Troubleshooting

### Issue: "data" folder not writable
**Solution:**
1. Set folder permissions to 755
2. Check that PHP runs under correct user
3. Ensure disk space is available

### Issue: JSON files corrupted
**Solution:**
1. Validate JSON syntax
2. Restore from backup
3. Reinitialize with init-data.php

### Issue: CORS errors on frontend
**Solution:**
Update config.php:
```php
header("Access-Control-Allow-Origin: https://yourdomain.com");
```

### Issue: Session not persisting
**Solution:**
1. Verify PHP session.save_path is writable
2. Check session timeout settings
3. Clear browser cookies and retry

---

## Frontend Integration

Create your frontend (HTML/Vue/React) to consume these APIs:

```javascript
// Example: Register user
async function register(email, password, name) {
  const response = await fetch('https://yourdomain.com/Pastpapers%20database/backend/api/register.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ email, password, name })
  });
  return await response.json();
}

// Example: Get questions
async function getQuestions() {
  const response = await fetch('https://yourdomain.com/Pastpapers%20database/backend/api/questions.php');
  return await response.json();
}

// Example: Post answer
async function postAnswer(questionId, content) {
  const response = await fetch('https://yourdomain.com/Pastpapers%20database/backend/api/answers.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    credentials: 'include',
    body: JSON.stringify({ question_id: questionId, content })
  });
  return await response.json();
}
```

---

## Admin Default Credentials
After initialization, log in as admin:
- **Email:** admin@pastpapers.com
- **Password:** admin123

**Change these immediately after first login!**

---

## Support & Maintenance

### Regular Maintenance Tasks
1. **Weekly:** Backup data folder
2. **Monthly:** Review user reputation integrity
3. **Quarterly:** Audit access logs
4. **Annually:** Update dependencies (if used)

### Performance Optimization
- Implement caching for leaderboard
- Archive old questions
- Clean up old sessions
- Monitor file sizes

---

## License & Credits

Created for educational purposes - Teaching TVET students web development with practical assignments.

Built with:
- Plain PHP (no frameworks)
- JSON file storage
- Vanilla JavaScript
- Hostinger hosting

---

## Support

For issues or improvements, contact your instructor or administrator.


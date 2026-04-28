# PASTPAPERS DATABASE - QUICK REFERENCE GUIDE

## System Overview
A complete learning platform where:
- **Admins** post past paper questions
- **Students** answer questions
- **Community voting** determines correct answers
- **Reputation system** rewards top contributors

---

## For STUDENTS

### Getting Started
1. Go to `frontend.html`
2. Click **Register** - Create account
3. Browse **Questions** from past papers
4. Click on a question to view details
5. Write your answer
6. Earn reputation points!

### Features Available
- 📚 **Browse Questions** - Sorted by difficulty (Easy/Medium/Hard)
- ✍️ **Post Answers** - Share your knowledge
- 👍 **Vote Answers** - Upvote helpful responses
- 📊 **Dashboard** - Track your progress
- 🏆 **Leaderboard** - See top contributors
- 🎖️ **Earn Badges** - Rising Star, Expert, Helpful

### Reputation System
| Action | Points |
|--------|--------|
| Post an answer | +10 |
| Receive 1 vote | +5 |
| Answer marked correct | +50 |

### My Tips for Success
✨ Answers with most votes = marked correct
✨ Best answers get pinned to top
✨ Earn badges at: 100pts (Rising Star), 500pts (Expert)
✨ Quality matters more than quantity!

---

## For ADMINS

### Admin Panel Access
- **URL:** `https://yourdomain.com/Pastpapers-database/backend/admin/index.html`
- **Username:** admin@pastpapers.com
- **Password:** admin123 (change immediately!)

### Admin Dashboard Tasks

#### 1. Post Questions
1. Click **📝 Post Question** in sidebar
2. Enter question details:
   - **Title:** e.g., "Computer Science - 2023 Paper 1"
   - **Description:** Brief context
   - **Difficulty:** Easy/Medium/Hard
   - **Questions:** JSON format array

**Question JSON Format:**
```json
[
  {
    "question_number": 1,
    "question_text": "What is a variable?",
    "marks": 2
  },
  {
    "question_number": 2,
    "question_text": "Write pseudocode to...",
    "marks": 5
  }
]
```

#### 2. View Statistics
Click **📊 Dashboard** to see:
- Total users registered
- Total questions posted
- Total answers submitted
- Total votes cast
- Top 5 contributors

#### 3. Manage Users
Click **👥 Users** to:
- View all registered users
- See reputation scores
- Change user roles (admin/user)
- Deactivate accounts if needed

#### 4. View Analytics
Click **📈 Analytics** to:
- Questions by difficulty
- Most voted answers
- Recent activity
- User engagement metrics

### Admin Decision: Mark Answer Correct
When viewing a question:
1. Review all answers
2. Click **Mark as Correct** on best answer
3. Author gets +50 reputation
4. Answer gets 🟢 Correct badge

---

## API Endpoints Reference

### Public Endpoints (No Login Required)
```
GET /api/questions.php              → Get all questions
GET /api/question-detail.php?id=X   → Get specific question with answers
GET /api/leaderboard.php            → Get top contributors
```

### Authentication
```
POST /api/register.php              → Create new account
POST /api/login.php                 → Login (returns session cookie)
POST /api/logout.php                → Logout
```

### Student Endpoints (Login Required)
```
POST /api/answers.php               → Submit an answer
POST /api/vote.php                  → Vote on an answer
GET  /api/dashboard.php             → Get my dashboard data
```

### Admin Endpoints (Admin Login Required)
```
POST /api/questions.php             → Post new question
GET  /api/admin-users.php           → Get all users
PUT  /api/admin-users.php           → Update user details
GET  /api/admin-stats.php           → Get platform statistics
POST /api/mark-correct.php          → Mark answer as correct
```

---

## File Locations

```
Pastpapers-database/
├── frontend.html                  ← Open this in browser (students)
├── HOSTINGER_DEPLOYMENT.md        ← Setup instructions
├── backend/
│   ├── config.php                 ← Configuration
│   ├── init-data.php              ← Initialize sample data (run once)
│   ├── README.md                  ← Full technical documentation
│   ├── includes/
│   │   ├── Database.php           ← JSON file handler
│   │   └── Auth.php               ← Authentication logic
│   ├── api/                       ← API endpoints
│   │   ├── register.php
│   │   ├── login.php
│   │   ├── logout.php
│   │   ├── questions.php
│   │   ├── answers.php
│   │   ├── vote.php
│   │   ├── dashboard.php
│   │   ├── leaderboard.php
│   │   ├── admin-users.php
│   │   └── admin-stats.php
│   ├── admin/
│   │   └── index.html             ← Admin dashboard (admins only)
│   └── data/                      ← JSON data storage
│       ├── users.json             ← User accounts
│       ├── questions.json         ← Posted questions
│       ├── answers.json           ← Student answers
│       ├── votes.json             ← Vote records
│       └── reputation.json        ← Reputation tracking
```

---

## Data Files (JSON Format)

### users.json
Stores all user accounts with hashed passwords

### questions.json
All posted questions with multiple sub-questions

### answers.json
All student answers with vote counts

### votes.json
Tracks who voted on each answer (one vote per student per answer)

### reputation.json
Tracks reputation points for each user

---

## Common Tasks

### As an Admin:

**Post a new exam:**
1. Click "Post Question"
2. Copy exam questions into JSON array
3. Set difficulty level
4. Click "Post Question"
5. ✅ Done! Students can now answer

**Promote a user to admin:**
1. Go to "Users" tab
2. Find user in list
3. Click "Edit"
4. Change role to "admin"
5. Save

**View top performers:**
1. Go to "Dashboard"
2. See "Top Contributors" table
3. Check leaderboard for detailed rankings

### As a Student:

**Answer a question:**
1. Click on question
2. Read all sub-questions
3. Write detailed answer
4. Click "Post Answer"
5. See votes increase as others appreciate your answer

**Check your progress:**
1. Click "Dashboard"
2. See reputation points
3. View badges earned
4. Review answer history

**Find best answers:**
1. Open a question
2. Answers auto-sorted by votes (highest first)
3. ✅ = Marked as correct by question author
4. 👍 = Community upvotes

---

## Keyboard Shortcuts

| Shortcut | Action |
|----------|--------|
| `?` | Show this help |
| `Ctrl+Enter` | Submit form (in some browsers) |
| `F5` | Refresh questions list |
| `Esc` | Close modal/dialog |

---

## Troubleshooting Quick Fixes

**Problem:** Can't login
- **Fix:** Check email/password. Register if no account.

**Problem:** Can't post answer
- **Fix:** Must be logged in. Refresh and try again.

**Problem:** Vote button doesn't work
- **Fix:** Can only vote once per answer. Already voted? Shows in UI.

**Problem:** Admin panel empty
- **Fix:** Make sure you're logged in as admin account.

**Problem:** No questions showing
- **Fix:** Admin hasn't posted any yet. Ask your instructor.

---

## Performance Tips

### For Students:
- ⚡ Keep answers concise but thorough
- ⚡ Vote on helpful answers (helps community)
- ⚡ Update profile avatar regularly

### For Admins:
- ⚡ Post questions weekly to keep platform active
- ⚡ Review and mark correct answers timely
- ⚡ Moderate inappropriate content
- ⚡ Backup data monthly

---

## Security Reminders

🔒 **DO:**
- Use strong passwords (8+ chars with mix)
- Log out when done
- Don't share login credentials
- Report suspicious activity

🔒 **DON'T:**
- Use same password as email
- Click links from unknown sources
- Leave computer unattended while logged in
- Store passwords in browsers on public computers

---

## Feature Roadmap

**Coming Soon:**
- 📱 Mobile app
- 🔔 Notifications
- 💬 Discussion forums
- 📥 Question importing from CSV
- 🎯 Performance analytics
- 🤝 Peer review system

---

## Support

**Need Help?**
- Check README.md in backend folder
- Review HOSTINGER_DEPLOYMENT.md for setup
- Check browser console (F12) for error messages

**Report Issues:**
- Email: admin@yourdomain.com
- Include: Screenshot, steps to reproduce, browser used

---

## Keyboard Navigation

- `Tab` - Move between form fields
- `Enter` - Submit forms
- `Space` - Scroll page / Select checkbox
- `Ctrl+S` - May save (browser dependent)

---

**Last Updated:** 2024
**Version:** 1.0
**Status:** Production Ready ✅


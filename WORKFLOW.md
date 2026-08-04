# EduPortal - WORKFLOW.md

Educational Student Management Portal
Author: **Mohd Yasin Kazi** - TY B.Sc. Computer Science

> **WARNING:**
> This application is intentionally vulnerable and is designed ONLY for educational demonstrations of SQL Injection in a controlled laboratory environment. It must never be deployed on a public server.

---

## 1. System Architecture

```
                       ┌────────────────────────────────────┐
                       │         Client (Browser)          │
                       │  HTML5 / CSS3 / Bootstrap 5 / JS  │
                       └────────────────┬───────────────────┘
                                        │  HTTP (GET / POST)
                                        ▼
                       ┌────────────────────────────────────┐
                       │       Flask (Werkzeug WSGI)       │
                       │    app.py -> all routes           │
                       │    templates/ -> Jinja2 pages      │
                       └────────────────┬───────────────────┘
                                        │
                       ┌────────────────▼───────────────────┐
                       │          database.py              │
                       │   get_db() -> sqlite3 connection  │
                       │   init_db() -> schema + seed      │
                       └────────────────┬───────────────────┘
                                        │
                       ┌────────────────▼───────────────────┐
                       │        SQLite (database.db)       │
                       │   students / admins / courses     │
                       │   faculty / results               │
                       └────────────────────────────────────┘
```

**Components:**
- `app.py` - all Flask routes + vulnerable queries
- `database.py` - SQLite connection + auto-seed from `database/eduportal.sql`
- `templates/` - Jinja2 templates (base.html + 10 pages)
- `assets/` - CSS, JS, images, vendored Bootstrap (offline)
- `api/index.py` + `vercel.json` - optional Vercel (Python) deployment

---

## 2. Database Flow

```
database/eduportal.sql
   │  DROP + CREATE TABLE students / admins / courses / faculty / results
   │  INSERT seed data (22 students, 5 faculty, 5 admins, 6 courses, 81 results)
   ▼
database.py -> init_db()  (runs on app startup, idempotent)
   │
   ▼
SQLite file: database.db  (or /tmp/eduportal.db on Vercel)
   ▲
   │  get_db() returns a new sqlite3 connection per request
   ▼
app.py routes run queries directly on the connection
```

**Table relationships:**
- `students.course_id` -> `courses.id` (one course has many students)
- `results.roll_no` -> `students.roll_no` (many results per student)
- `faculty` and `admins` are standalone tables

---

## 3. Login Flow (Student)

```
User submits username + password (POST /student-login)
          │
          ▼
app.py -> student_login()
   sql = "SELECT * FROM students
          WHERE username = '" + username + "' AND password = '" + password + "'"
   rows = cursor.execute(sql).fetchall()        ← VULNERABLE
          │
          ├── if rows > 0 ──► session['student_id'] = id, name, roll
          │                        │
          │                        ▼
          │                  redirect -> /dashboard
          │                        │
          │                        ▼
          │                  dashboard route loads profile + results
          │
          └── if 0 rows / error ──► "Invalid username or password"
```

**Attack:** `password = ' OR '1'='1` makes the WHERE clause always true.

---

## 4. Search Flow (Student Search)

```
User submits Roll Number (POST /student-search)
          │
          ▼
app.py -> student_search()
   sql = "SELECT s.*, c.name AS course_name
          FROM students s
          LEFT JOIN courses c ON s.course_id = c.id
          WHERE s.roll_no = '" + roll + "'"          ← VULNERABLE
          │
          ▼
   cursor.execute(sql).fetchall()
          │
          ├── found ──► display Name / Course / Semester / Email card
          └── not found / error ──► "No student found"
```

The executed SQL is shown in a `sql-box` on the page for teaching.

---

## 5. Result Flow

```
User submits Roll Number (POST /results)
          │
          ▼
app.py -> results()
   sql = "SELECT * FROM results
          WHERE roll_no = '" + roll + "'
          ORDER BY semester DESC, subject ASC"        ← VULNERABLE
          │
          ▼
   cursor.execute(sql).fetchall()
          │
          ├── rows > 0 ──► build summary (subjects, marks, % , grade)
          │                    └── render table: Subject/Marks/%/Grade
          └── 0 rows ──► "No results published"
```

**Attack (UNION):** append `' UNION SELECT ... FROM students WHERE '1'='1` to dump other tables.

---

## 6. SQL Query Flow (Vulnerable Pattern)

```
POST roll_no   =  "BCS2026-001' UNION SELECT ... -- "
        │
        │  direct concatenation (NO escaping)
        ▼
sql = "SELECT * FROM results WHERE roll_no = '" + roll + "' ..."
        ▼
SQLite parses the attacker's extra SQL as part of the query
        ▼
Attacker controls the WHERE clause / result set / tables read
```

**Why it works:** the developer concatenates user input directly into the SQL string. Quotes are never escaped, so input such as `'` closes the string literal and the rest becomes valid SQL.

---

## 7. Folder Structure

```
EduPortal/
├── app.py                  Flask application (all routes)
├── database.py             SQLite connection + auto-seed
├── requirements.txt        Flask dependency
├── vercel.json             Vercel Python runtime config
├── api/
│   └── index.py            WSGI entry point (Vercel)
├── templates/
│   ├── base.html           Navbar + footer layout
│   ├── index.html          Home / landing
│   ├── about.html          About page
│   ├── courses.html        Course list (DB)
│   ├── faculty.html        Faculty list (DB)
│   ├── student_login.html  Vulnerable student login
│   ├── admin_login.html    Vulnerable admin login
│   ├── student_search.html Vulnerable roll-number search
│   ├── results.html        Vulnerable result lookup
│   ├── dashboard.html      Student / Admin dashboard
│   └── contact.html        Contact page
├── assets/
│   ├── css/style.css       App styles
│   ├── js/script.js        Small JS helpers
│   ├── images/logo.svg     Logo
│   └── vendor/             Bootstrap 5 + Bootstrap Icons (offline)
├── database/eduportal.sql  Schema + seed data
├── README.md               Setup guide
├── REPORT.md               Full project report
├── PPT.md                  10 presentation slides
├── VIVA.md                 30 viva questions & answers
└── WORKFLOW.md             This document
```

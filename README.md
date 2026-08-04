# EduPortal

Educational Student Management Portal - a classroom laboratory for demonstrating **SQL Injection** vulnerabilities.

**Author:** Mohd Yasin Kazi | **Class:** TY B.Sc. Computer Science
**Stack:** Python (Flask) + SQLite + Bootstrap 5

> **WARNING**
> This application is **intentionally vulnerable** and is designed ONLY for educational demonstrations of SQL Injection in a controlled laboratory environment. **It must never be deployed on a public server.**

---

## Overview

EduPortal looks and behaves like a real university ERP:

- Home / About / Courses / Faculty / Contact pages
- **Student Login** and **Admin Login**
- **Student Search** by roll number
- **Result Portal** (marks, percentage, grade)
- **Student Dashboard** and **Admin Dashboard**

Every database query is built with **raw SQL string concatenation** - no prepared statements, no escaping, no parameterisation. That makes the portal a safe, self-contained lab for learning how SQL Injection works and how to prevent it.

## Technologies

| Layer     | Technology                          |
|-----------|-------------------------------------|
| Frontend  | HTML5, CSS3, Bootstrap 5, JavaScript|
| Backend   | Python 3 + Flask                    |
| Database  | SQLite 3                            |
| Deploy    | Local (Flask) / Vercel (WSGI)       |

## Installation (Local)

1. **Install Python 3.10+** from https://www.python.org/downloads/

2. **Install Flask**
   ```bash
   pip install -r requirements.txt
   ```

3. **Run the application**
   ```bash
   python app.py
   ```

4. **Open in browser**
   ```
   http://127.0.0.1:5000
   ```

The SQLite database is created and seeded automatically on startup from `database/eduportal.sql` (22 students, 5 admins, 6 courses, 5 faculty, 81 results). To reset the database, delete `database.db` and restart.

## Demo Users

| Username | Password   | Role    |
|----------|------------|---------|
| aarav01  | student123 | Student |
| admin    | admin123   | Admin   |

Other students use username pattern `<roll-prefix>-NN` (e.g. `ishita02`), all with password `student123`.

## Demonstration Steps

1. Open the **Student Login** page.
2. Login normally with `aarav01` / `student123` - it works.
3. **Attack - Login bypass:** enter `' OR '1'='1` as username and password - login succeeds as the first student.
4. **Attack - Comment bypass:** enter `aarav01' -- ` as username, any password - the password check is commented out.
5. **Attack - UNION extraction (Result Portal):** submit roll number:
   ```
   BCS2026-001' UNION SELECT id, username, username || ' : ' || password, 1, 2, 3.50, 'A', 'Exam', semester FROM students WHERE '1'='1
   ```
   Every student's username and password appears in the result table.
6. Observe the **executed SQL** shown on each page after a search/login attempt.

## Vulnerable Code Pattern

```python
# app.py - student login (INTENTIONALLY VULNERABLE)
sql = ("SELECT * FROM students WHERE username = '"
       + username
       + "' AND password = '"
       + password
       + "'")
rows = db.execute(sql).fetchall()   # user input runs as SQL
```

**The fix (prepared / parameterised statement):**

```python
sql = "SELECT * FROM students WHERE username = ? AND password = ?"
rows = db.execute(sql, (username, password)).fetchall()
```

The `?` placeholder binds user input as data, so it can never change the query structure.

## Project Structure

```
EduPortal/
├── app.py              # Flask application (all routes)
├── database.py         # SQLite connection + auto-seed
├── requirements.txt    # flask
├── vercel.json         # Vercel Python runtime config
├── api/index.py        # WSGI entry point for Vercel
├── templates/          # base.html + 10 page templates (Jinja2)
├── assets/             # CSS, JS, images, vendored Bootstrap (offline)
├── database/
│   └── eduportal.sql   # SQLite schema + seed data
├── README.md           # This file
├── REPORT.md           # Full project report
├── WORKFLOW.md         # Architecture & data flows
├── PPT.md              # 10 presentation slides
└── VIVA.md             # 30 viva questions & answers
```

## Deployment (Vercel)

The project includes a `vercel.json` and `api/index.py` WSGI handler, so it can run on Vercel's Python runtime. On Vercel the SQLite file is created in `/tmp` and re-seeded on each cold start - acceptable for a classroom demo.

## Educational Purpose Only

This project demonstrates how SQL Injection works and why parameterised queries matter. **Do not** reuse the vulnerable patterns in any real application.

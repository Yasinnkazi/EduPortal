# EduPortal - Project Report

## Educational Student Management Portal
### A Classroom Laboratory for Demonstrating SQL Injection Vulnerabilities

---

**Author:** Mohd Yasin Kazi
**Class:** TY B.Sc. Computer Science
**Academic Year:** 2026-27

---

> **WARNING:**
> This application is intentionally vulnerable and is designed ONLY for educational demonstrations of SQL Injection in a controlled laboratory environment. It must never be deployed on a public server.

---

## Certificate

This is to certify that **Mohd Yasin Kazi**, a student of **TY B.Sc. Computer Science**, has successfully completed the project titled **"EduPortal - Educational Student Management Portal"** for the academic year 2026-27. The project demonstrates the working of SQL Injection vulnerabilities for educational purposes in a controlled laboratory environment.

**Guide / Evaluator:** ____________________

**Signature:** ____________________

**Date:** ____________________

---

## Acknowledgement

I would like to express my sincere gratitude to my project guide and the Department of Computer Science for their valuable guidance and support throughout the development of this project. I also thank my classmates who helped test the application and explore its behaviour during classroom demonstrations. Finally, I thank my family for their constant encouragement.

---

## Abstract

Web applications store and serve data through databases. When user-supplied input is inserted directly into a SQL query without any sanitisation, an attacker can modify the query's structure and gain unauthorised access to data. This vulnerability is called **SQL Injection**.

**EduPortal** is a realistic college student management portal developed in Python with the Flask framework and SQLite that intentionally contains SQL Injection vulnerabilities. The portal provides student login, admin login, student search by roll number, a result portal, and administrative dashboards - exactly like a real university ERP. Because the application is built with direct SQL concatenation (no prepared statements, no escaping), it forms a safe, self-contained laboratory for teaching students how SQL Injection works, how attacks are crafted, and how the same queries can be rewritten safely.

This report documents the design, database schema, workflows, vulnerable code patterns, attack demonstrations, and the educational value of the project.

---

## 1. Introduction

### 1.1 Background
Databases are the backbone of most web applications. Python + Flask is one of the most widely taught web stacks in undergraduate Computer Science programmes. Security, however, is often neglected in basic CRUD tutorials, and students first learn "the way it is usually done" - building SQL strings by concatenating variables.

### 1.2 Problem Statement
When a developer writes:

```python
sql = f"SELECT * FROM users WHERE username = '{username}' AND password = '{password}'"
```

the variables `username` and `password` become part of the SQL grammar. A malicious user can type input such as `' OR '1'='1` which changes the meaning of the entire query. This single mistake - SQL Injection - consistently ranks in the OWASP Top 10 of web application risks.

### 1.3 The Project
EduPortal provides a controlled environment where the vulnerability is **intentional and isolated** (local Flask server, seeded dummy data, no real information), so students can:

1. See exactly how user input flows into a SQL query.
2. Practise crafting injection payloads.
3. Observe the impact of classic attacks (login bypass, UNION-based extraction).
4. Compare with the secure implementation using prepared statements.

### 1.4 Scope
The scope is limited to a classroom / laboratory setting. The project does not contain real personal data, does not connect to external networks, and is accompanied by prominent warnings not to deploy it publicly.

---

## 2. Objectives

The main objectives of this project are:

1. To build a realistic, professional-looking student management portal using Python and Flask.
2. To implement the portal using **intentionally vulnerable** SQL queries (direct string concatenation) for classroom demonstration.
3. To demonstrate SQL Injection login bypass techniques (`OR` and comment-based).
4. To demonstrate **UNION-based** data extraction through search features.
5. To help students understand how prepared statements and parameterised queries prevent injection.
6. To reinforce UI/UX skills (Bootstrap 5, responsive design) alongside security education.

---

## 3. System Requirements

### 3.1 Hardware Requirements
- Processor: Intel/AMD, 1 GHz or faster
- RAM: 2 GB minimum (4 GB recommended)
- Disk space: 200 MB free
- Display: 1024x768 or higher

### 3.2 Software Requirements
- Windows 10/11 / Linux / macOS (any OS running Python)
- Python 3.10 or higher
- Flask 3.x (installed via pip)
- Any modern web browser (Chrome, Edge, Firefox)

### 3.3 User Requirements
- Basic knowledge of Python and SQL
- Understanding of how a web request reaches a database

---

## 4. Software Used

| Software       | Version | Purpose                       |
|----------------|---------|-------------------------------|
| Python         | 3.11    | Programming language          |
| Flask          | 3.1     | Web framework (backend)       |
| SQLite         | 3.x     | Embedded relational database  |
| Werkzeug       | 3.x     | WSGI server (ships with Flask)|
| Bootstrap 5    | 5.3     | Responsive UI framework       |
| Bootstrap Icons| 1.11    | Icon set                      |
| HTML5 / CSS3   | -       | Page structure & styling      |
| JavaScript     | ES6     | Small client-side helpers     |
| Jinja2         | 3.x     | Templating engine (Flask)     |

---

## 5. Database Design

Database: `database.db` (SQLite file, auto-created from `database/eduportal.sql`)

### 5.1 Entity Relationship

```
courses ─1───* students ─1───* results
   │                            │
   │                            │
 admins (standalone)          faculty (standalone)
```

### 5.2 Table: `students`
| Field     | Type          | Notes               |
|-----------|---------------|---------------------|
| id        | INTEGER PK AI |                     |
| roll_no   | VARCHAR(20) UQ| e.g. BCS2026-001    |
| name      | VARCHAR(100)  |                     |
| username  | VARCHAR(50) UQ| login name          |
| password  | VARCHAR(255)  | plain text (demo)   |
| course_id | INT           | FK -> courses.id    |
| semester  | INT           |                     |
| email     | VARCHAR(100)  |                     |
| phone     | VARCHAR(20)   |                     |
| address   | VARCHAR(255)  |                     |
| city      | VARCHAR(50)   |                     |

### 5.3 Table: `admins`
| Field    | Type          |
|----------|---------------|
| id       | INTEGER PK AI |
| username | VARCHAR(50) UQ|
| password | VARCHAR(255)  |
| full_name| VARCHAR(100)  |
| email    | VARCHAR(100)  |
| role     | VARCHAR(50)   |

### 5.4 Table: `courses`
| Field      | Type          |
|------------|---------------|
| id         | INTEGER PK AI |
| code       | VARCHAR(20) UQ|
| name       | VARCHAR(100)  |
| department | VARCHAR(80)   |
| duration   | VARCHAR(20)   |
| seats      | INT           |
| fee        | DECIMAL(10,2) |
| description| TEXT          |

### 5.5 Table: `faculty`
| Field        | Type         |
|--------------|--------------|
| id           | INTEGER PK AI|
| name         | VARCHAR(100) |
| designation  | VARCHAR(80)  |
| department   | VARCHAR(80)  |
| qualification| VARCHAR(100) |
| email        | VARCHAR(100) |
| phone        | VARCHAR(20)  |

### 5.6 Table: `results`
| Field     | Type         |
|-----------|--------------|
| id        | INTEGER PK AI|
| roll_no   | VARCHAR(20)  |
| subject   | VARCHAR(100) |
| marks     | INT          |
| total     | INT (default 100) |
| percentage| DECIMAL(5,2) |
| grade     | VARCHAR(5)   |
| exam_type | VARCHAR(30)  |
| semester  | INT          |

### 5.7 Seed Data Summary
| Table   | Rows |
|---------|------|
| students| 22   |
| admins  | 5    |
| courses | 6    |
| faculty | 5    |
| results | 81   |

---

## 6. System Workflow

### 6.1 Home Flow
1. User opens `http://127.0.0.1:5000/`.
2. `index` route runs; Flask queries SQLite for course & faculty counts and lists.
3. Banner, notices, course cards, faculty preview and disclaimer render.

### 6.2 Login Flow
1. User submits username/password at `/student-login`.
2. Flask builds SQL by concatenation and runs it with `cursor.execute(sql)`.
3. If rows are returned, a session is created and the user is redirected to `/dashboard`.
4. If not, an "Invalid credentials" message is shown.

### 6.3 Search Flow
1. User enters a roll number at `/student-search`.
2. The concatenated `SELECT` runs; a student card or "not found" message renders.
3. The executed query is displayed in a highlighted SQL box for learning.

### 6.4 Result Flow
1. User enters a roll number at `/results`.
2. All matching result rows are fetched.
3. Flask computes totals, percentage and grade, then renders a table + summary tiles.

### 6.5 Dashboard Flow
- **Student dashboard:** profile card + list of the student's results.
- **Admin dashboard:** counts + three tabs (Student List, Course List, Faculty List).

---

## 7. Website Screenshots

Screenshots for the report can be captured as follows (the application is running):

1. **Home page** - `http://127.0.0.1:5000/`
2. **Student login** - `http://127.0.0.1:5000/student-login`
3. **Admin login** - `http://127.0.0.1:5000/admin-login`
4. **Student search (result shown)** - submit `BCS2026-001`
5. **Result portal** - submit `BCS2026-001`
6. **Student dashboard** - login with `aarav01` / `student123`
7. **Admin dashboard** - login with `admin` / `admin123`
8. **SQL injection demo** - submit `' OR '1'='1` as password

*(Each page also shows the exact SQL query executed, making screenshots excellent teaching material.)*

---

## 8. How SQL Queries Work (Vulnerable Implementation)

### 8.1 The Vulnerable Pattern
All database access in EduPortal follows the same pattern:

```python
# app.py - student login (INTENTIONALLY VULNERABLE)
username = request.form.get("username", "")
password = request.form.get("password", "")

sql = ("SELECT * FROM students WHERE username = '"
       + username
       + "' AND password = '"
       + password
       + "'")

rows = db.execute(sql).fetchall()   # no escaping, no parameters
```

### 8.2 Normal Execution
User enters: `username = aarav01`, `password = student123`

```sql
SELECT * FROM students
WHERE username = 'aarav01' AND password = 'student123';
```

SQLite matches exactly one row - login succeeds.

### 8.3 Attack - OR Injection
User enters: `username = anything`, `password = ' OR '1'='1`

```sql
SELECT * FROM students
WHERE username = 'anything' AND password = '' OR '1'='1';
```

Because `AND` binds tighter than `OR`, the condition becomes:
`(username='anything' AND password='') OR (1=1)`

The second clause is always true, so **every row matches** - login bypassed.

### 8.4 Attack - Comment Injection
User enters: `username = ' OR '1'='1' -- `, `password = anything`

```sql
SELECT * FROM students
WHERE username = '' OR '1'='1' -- ' AND password = 'anything';
```

The `--` comment removes the password check. Login bypassed.

### 8.5 Attack - UNION Extraction
User enters the following in the Result Portal roll number:

```
BCS2026-001' UNION SELECT id, username, username || ' : ' || password, 1, 2, 3.50, 'A', 'Exam', semester FROM students WHERE '1'='1
```

```sql
SELECT * FROM results WHERE roll_no = 'BCS2026-001'
UNION
SELECT id, username, username || ' : ' || password, 1, 2, 3.50, 'A', 'Exam', semester
FROM students WHERE '1'='1'
ORDER BY semester DESC, subject ASC;
```

The `UNION` appends rows from the `students` table to the `results` output, leaking every username and password through the result table.

### 8.6 The Fix (Prepared Statements)
```python
sql = "SELECT * FROM students WHERE username = ? AND password = ?"
rows = db.execute(sql, (username, password)).fetchall()
```

Prepared statements separate the SQL structure from the data (`?` placeholders + bound parameters). User input can never change the meaning of the query, so injection is impossible.

---

## 9. Educational Purpose

1. **Hands-on security learning:** students see real vulnerabilities, not just theory.
2. **Safe environment:** local Flask + dummy data means no real harm.
3. **Visual feedback:** every page displays the exact query executed.
4. **Complete narrative:** a single small app demonstrates multiple injection classes (login bypass, comment injection, UNION extraction).
5. **Fix-forward approach:** the report and README show the secure replacement code.
6. **OWASP awareness:** aligns with SQL Injection in the OWASP Top 10.

---

## 10. Advantages

- Realistic ERP-style interface (modern, professional, responsive).
- Zero external services - SQLite is embedded, nothing to install except Flask.
- Lightweight and fast; no external libraries beyond Bootstrap.
- Clearly isolated lab data - no risk to real information.
- Educational aids built in (executed-SQL display, demo credentials).
- Easy to reset: delete `database.db` and restart to re-seed.

---

## 11. Limitations

- The app is **intentionally insecure** and must never be deployed publicly.
- Passwords are stored in plain text (part of the demonstration).
- No CSRF protection, rate limiting, or brute-force protection (by design).
- No file uploads or advanced features such as attendance/grading (out of scope).
- Demonstrates SQL Injection only; other OWASP risks are out of scope.

---

## 12. Future Scope

1. Add **prepared-statement versions** of the same pages behind a "Secure Mode" toggle, so students can compare vulnerable vs. secure side by side.
2. Add an **SQL Injection challenge module** with levels (easy -> hard) and scoring.
3. Introduce a **query log viewer** so the instructor can review student attempts.
4. Add a **solutions/hints** panel with explanations of each payload.
5. Extend to other injection classes (XSS, CSRF) as separate lab modules.

---

## 13. Conclusion

EduPortal successfully fulfils its objective of providing a realistic, classroom-safe laboratory for understanding SQL Injection. The portal behaves like a genuine university ERP yet is built on intentionally vulnerable SQL, allowing students to observe first-hand how unsanitised input reaches a database and changes query behaviour. Login bypass and UNION-based extraction were demonstrated and verified. The project also highlights the correct defensive technique - prepared statements - making the learning cycle complete: see the flaw, exploit it, understand it, and fix it.

---

## 14. Bibliography

1. OWASP Foundation, "OWASP Top 10 - A03:2021 Injection", https://owasp.org/Top10/
2. OWASP, "SQL Injection Prevention Cheat Sheet", https://cheatsheetseries.owasp.org/
3. W3Schools, "SQL Injection", https://www.w3schools.com/sql/sql_injection.asp
4. Flask Documentation, "Application Setup / Database", https://flask.palletsprojects.com/
5. Python Documentation, "sqlite3 - DB-API 2.0 interface", https://docs.python.org/3/library/sqlite3.html
6. SQLite Documentation, "UNION / SELECT", https://sqlite.org/lang_select.html

---

**Disclaimer:**
**WARNING:** This application is intentionally vulnerable and is designed ONLY for educational demonstrations of SQL Injection in a controlled laboratory environment. It must never be deployed on a public server.

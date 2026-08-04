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
                       │           Apache (XAMPP)          │
                       │          Serves .php pages         │
                       └────────────────┬───────────────────┘
                                        │
                       ┌────────────────▼───────────────────┐
                       │             PHP 8 Engine          │
                       │   config.php -> mysqli connection  │
                       │   pages -> direct SQL concatenation│
                       └────────────────┬───────────────────┘
                                        │
                       ┌────────────────▼───────────────────┐
                       │             MySQL (3306)           │
                       │   students / admins / courses      │
                       │   faculty / results                │
                       └────────────────────────────────────┘
```

**Components:**
- `config.php` - DB connection + session start
- `includes/header.php` / `footer.php` - shared navbar & footer
- `assets/` - CSS, JS, images, vendored Bootstrap (offline)
- `database/eduportal.sql` - schema + seed data

---

## 2. Database Flow

```
eduportal.sql
   │  CREATE DATABASE eduportal
   │  CREATE TABLE students / admins / courses / faculty / results
   │  INSERT seed data (22 students, 5 faculty, 5 admins, 6 courses, 81 results)
   ▼
MySQL server (localhost:3306)
   ▲
   │  mysqli_connect("localhost","root","","eduportal")
   │  $conn = mysqli_connect(...)  in config.php
   ▼
Every PHP page opens a NEW connection per request (no pooling)
   ▼
Queries run directly on the connection, results returned to PHP
```

**Table relationships:**
- `students.course_id` -> `courses.id` (one course has many students)
- `results.roll_no` -> `students.roll_no` (many results per student)
- `faculty` and `admins` are standalone tables

---

## 3. Login Flow (Student)

```
User submits username + password (POST)
          │
          ▼
student-login.php
   $sql = "SELECT * FROM students
           WHERE username = '$username' AND password = '$password'"
   $result = mysqli_query($conn, $sql);        ← VULNERABLE
          │
          ├── if rows > 0 ──► set $_SESSION (student_id, name, roll)
          │                        │
          │                        ▼
          │                  header("Location: dashboard.php")
          │                        │
          │                        ▼
          │                  dashboard.php loads student profile + results
          │
          └── if 0 rows ──► display "Invalid username or password"
```

**Attack:** `password = ' OR '1'='1` makes the WHERE clause always true.

---

## 4. Search Flow (Student Search)

```
User submits Roll Number (POST)
          │
          ▼
student-search.php
   $sql = "SELECT s.*, c.name AS course_name
           FROM students s
           LEFT JOIN courses c ON s.course_id = c.id
           WHERE s.roll_no = '$roll'"              ← VULNERABLE
          │
          ▼
   mysqli_query($conn, $sql)
          │
          ├── found ──► display Name / Course / Semester / Email card
          └── not found / error ──► "No student found"
```

The executed SQL is shown in a `sql-box` on the page for teaching.

---

## 5. Result Flow

```
User submits Roll Number (POST)
          │
          ▼
results.php
   $sql = "SELECT * FROM results
           WHERE roll_no = '$roll'
           ORDER BY semester DESC, subject ASC"    ← VULNERABLE
          │
          ▼
   mysqli_query($conn, $sql)
          │
          ├── rows > 0 ──► build summary (subjects, marks, % , grade)
          │                    └── render table: Subject/Marks/%/Grade
          └── 0 rows ──► "No results published"
```

**Attack (UNION):** append `' UNION SELECT ... FROM students WHERE '1'='1` to dump other tables.

---

## 6. SQL Query Flow (Vulnerable Pattern)

```
$_POST['roll_no']   =  "BCS2026-001' UNION SELECT ... -- "
        │
        │  direct concatenation (NO escaping)
        ▼
$sql = "SELECT * FROM results WHERE roll_no = '" . $roll . "' ..."
        ▼
MySQL parses the attacker's extra SQL as part of the query
        ▼
Attacker controls the WHERE clause / result set / tables read
```

**Why it works:** the developer concatenates user input directly into the SQL string. Quotes are never escaped, so input such as `'` closes the string literal and the rest becomes valid SQL.

---

## 7. Folder Structure

```
EduPortal/
├── index.php              Home / landing
├── about.php              About page
├── courses.php            Course list (DB)
├── faculty.php            Faculty list (DB)
├── student-login.php      Vulnerable student login
├── admin-login.php        Vulnerable admin login
├── student-search.php     Vulnerable roll-number search
├── results.php            Vulnerable result lookup
├── dashboard.php          Student / Admin dashboard
├── contact.php            Contact page
├── logout.php             Clears session
├── config.php             DB connection
├── includes/              header.php, footer.php
├── assets/
│   ├── css/style.css      App styles
│   ├── js/script.js       Small JS helpers
│   ├── images/logo.svg    Logo
│   └── vendor/            Bootstrap 5 + Bootstrap Icons (offline)
├── database/eduportal.sql Database dump + seed data
├── README.md              Setup guide
├── REPORT.md              ~20 page project report
├── PPT.md                 10 presentation slides
├── VIVA.md                30 viva questions & answers
└── WORKFLOW.md            This document
```

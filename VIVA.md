# EduPortal - VIVA Questions & Answers (30)

**Mohd Yasin Kazi** - TY B.Sc. Computer Science

> **WARNING:**
> This application is intentionally vulnerable and is designed ONLY for educational demonstrations of SQL Injection in a controlled laboratory environment. It must never be deployed on a public server.

---

## Basic Concepts

**Q1. What is SQL Injection?**
**A.** SQL Injection is a web security vulnerability where an attacker inserts (injects) malicious SQL code into a query by manipulating user input. If the input is concatenated directly into a SQL string, it becomes part of the query and can bypass authentication, read, modify or delete data.

**Q2. Why is SQL Injection considered critical?**
**A.** It can expose the entire database - usernames, passwords, financial data - and in some cases give attackers full control of the server. It has consistently appeared in the OWASP Top 10 risks.

**Q3. Which OWASP category does SQL Injection belong to?**
**A.** A03:2021 - Injection (SQL, NoSQL, OS Command, etc.).

**Q4. What is a web application?**
**A.** An application that runs on a server and is accessed through a browser over HTTP/HTTPS, e.g., a student portal, e-commerce site or ERP.

**Q5. What is a database?**
**A.** An organised collection of data stored and managed by a database management system (DBMS) such as SQLite or MySQL. Data is stored in tables with rows and columns.

## Technology

**Q6. Which languages are used in EduPortal?**
**A.** Python 3 with the Flask framework for the backend, HTML5 + CSS3 + Bootstrap 5 + JavaScript for the frontend, and SQLite for the database. Templates are rendered with Jinja2.

**Q7. What is Flask?**
**A.** Flask is a lightweight web framework for Python. It provides routing (mapping URLs to functions), request handling, session management and template rendering, and includes a built-in development server (Werkzeug).

**Q8. What is SQLite and why was it chosen?**
**A.** SQLite is an embedded, file-based relational database. It needs no separate server or configuration - the whole database is one file (`database.db`) created and seeded automatically on startup. This makes the lab easy to set up and reset.

**Q9. How does Python connect to SQLite in this project?**
**A.** Using the built-in `sqlite3` module. `database.py` provides `get_db()` which opens a connection with `row_factory = sqlite3.Row` so query results can be accessed like dictionaries (e.g. `row["name"]`).

**Q10. Why is Bootstrap used?**
**A.** Bootstrap 5 provides responsive layout, pre-built components (navbar, cards, tables, buttons, forms) and icons, giving the portal a professional ERP look with minimal custom CSS.

## The Vulnerable Code

**Q11. Where is the vulnerable code in EduPortal?**
**A.** In every database interaction: the `student_login`, `admin_login`, `student_search`, `results` and `dashboard` routes in `app.py`. All use direct string concatenation of user input into SQL.

**Q12. Show the vulnerable login query.**
**A.**
```python
sql = ("SELECT * FROM students WHERE username = '"
       + username
       + "' AND password = '"
       + password
       + "'")
rows = cursor.execute(sql).fetchall()
```

**Q13. What is a prepared statement?**
**A.** A prepared statement separates SQL structure from data. The query is sent to the DB once with placeholders (`?`), and user data is bound separately as parameters, so input can never alter the query logic. Example: `cursor.execute(sql, (username, password))`.

**Q14. Why is `cursor.execute(sql)` dangerous here?**
**A.** `execute(sql)` runs whatever SQL string it is given. Because the string contains unsanitised user input, an attacker can inject additional SQL that SQLite happily executes.

**Q15. What does "sanitisation" mean?**
**A.** Cleaning or escaping user input so it is treated only as data, not as SQL (e.g. `sqlite3` `?` placeholders, escaping quotes), plus validating input type/length.

## Attack Demonstrations

**Q16. How does the `' OR '1'='1` login bypass work?**
**A.** Entering `' OR '1'='1` as the password makes the query:
```sql
WHERE username='x' AND password='' OR '1'='1'
```
Due to operator precedence (`AND` before `OR`) this is `(false) OR (true)` = always true, so the query returns every student row and login succeeds.

**Q17. What is comment-based injection?**
**A.** Using `--` (SQL comment) to remove the rest of the query. For example `aarav01' -- ` turns:
```sql
WHERE username='aarav01' -- ' AND password='anything'
```
The password check is commented out.

**Q18. What is a UNION-based attack?**
**A.** `UNION` merges the result of two SELECT queries. An attacker appends `UNION SELECT ... FROM other_table` to leak data from other tables, e.g. dumping `students.username` and `students.password` through the result portal.

**Q19. What condition must the UNION satisfy?**
**A.** Both SELECT statements must return the **same number of columns**, and column data types should be compatible.

**Q20. How do you know how many columns a table has in a UNION attack?**
**A.** By trying `ORDER BY n` or `UNION SELECT 1`, `UNION SELECT 1,2`, `UNION SELECT 1,2,3`, ... until the query works without a "different number of columns" error. EduPortal's `results` table has 9 columns.

## Impact & Prevention

**Q21. What damage can SQL Injection cause?**
**A.** Authentication bypass, reading sensitive data, modifying/deleting records, extracting passwords, and potentially gaining shell access to the server.

**Q22. Why does EduPortal store passwords in plain text?**
**A.** It is part of the demonstration - so students can see the leak clearly. In real systems passwords must be hashed (e.g., bcrypt / `werkzeug.security.generate_password_hash`).

**Q23. How can SQL Injection be prevented?**
**A.** 1) Prepared statements / parameterised queries (primary defence). 2) Input validation (type, length, whitelist). 3) Principle of least privilege (DB user with minimal permissions). 4) Never concatenate user input into SQL.

**Q24. Write the secure version of the login query.**
**A.**
```python
sql = "SELECT * FROM students WHERE username = ? AND password = ?"
rows = cursor.execute(sql, (username, password)).fetchall()
```

**Q25. What is the principle of least privilege?**
**A.** Giving each account (application/DB/user) only the minimum permissions it needs. Even if injected, an attacker then cannot perform operations beyond that account's rights.

## Project-Specific

**Q26. Which pages does EduPortal contain?**
**A.** Home (`/`), About, Courses, Faculty, Student Login, Admin Login, Student Search, Result Portal, Dashboard, Contact, plus a Logout route - all in `app.py` with templates under `templates/`.

**Q27. What is stored in the `results` table?**
**A.** Roll number, subject, marks, total, percentage, grade, exam type and semester - 81 rows seeded for demonstration.

**Q28. How is the dashboard different for students vs admins?**
**A.** A student sees their profile and their own results. An admin sees counts and tabs for Student List, Course List and Faculty List.

**Q29. Why does the executed query appear on the search/result pages?**
**A.** It is an intentional teaching aid - students can see exactly how their input (or injection payload) changed the SQL before it ran.

**Q30. Can this project be deployed on a public server?**
**A.** **No.** It is intentionally vulnerable and must stay in a controlled classroom/lab environment only. This warning is repeated in the README, report, home page and footer.

---

**Warning:** This application is intentionally vulnerable and is designed ONLY for educational demonstrations of SQL Injection in a controlled laboratory environment. It must never be deployed on a public server.

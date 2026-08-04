"""
EduPortal - Educational Student Management Portal (Flask version)

Educational SQL Injection laboratory. EVERY database query in this project
is built with raw SQL string concatenation - no prepared statements, no
escaping, no parameterisation. This is INTENTIONAL so students can see how
SQL Injection works and why it must be prevented.

WARNING: intentionally vulnerable application. Classroom / controlled lab
use ONLY. Must never be deployed on a public server.

Author: Mohd Yasin Kazi | TY B.Sc. Computer Science
"""

import os
import sqlite3
from datetime import datetime

from flask import (
    Flask,
    redirect,
    render_template,
    request,
    session,
    url_for,
)

from database import get_db, init_db

app = Flask(__name__, static_folder="assets", static_url_path="/assets")
app.secret_key = "eduportal-sqli-lab-2026"

# Create + seed the SQLite database on startup (idempotent). On Vercel the
# file lives in /tmp and is re-seeded on every cold start - fine for a lab.
init_db()

NAV_ITEMS = [
    {"label": "Home", "endpoint": "index", "icon": "bi-house-door"},
    {"label": "About", "endpoint": "about", "icon": "bi-info-circle"},
    {"label": "Courses", "endpoint": "courses", "icon": "bi-journal-bookmark"},
    {"label": "Faculty", "endpoint": "faculty", "icon": "bi-people"},
    {"label": "Student Search", "endpoint": "student_search", "icon": "bi-search"},
    {"label": "Result Portal", "endpoint": "results", "icon": "bi-award"},
    {"label": "Contact", "endpoint": "contact", "icon": "bi-envelope"},
]


@app.context_processor
def inject_nav():
    return {"nav_items": NAV_ITEMS, "now": datetime.now()}


@app.template_filter("money")
def money(value):
    """Thousands grouping: e.g. 45000.0 -> '45,000'."""
    return f"{float(value):,.0f}"


@app.template_filter("pct")
def pct(value):
    """Two-decimal format: e.g. 88.0 -> '88.00'."""
    return f"{float(value):.2f}"


def grade_for(pct):
    if pct >= 90:
        return "A+"
    if pct >= 80:
        return "A"
    if pct >= 70:
        return "B+"
    if pct >= 60:
        return "B"
    if pct >= 50:
        return "C"
    if pct >= 40:
        return "D"
    return "F"


def query(sql, args=()):
    """Run a query inside a connection and return rows (or None on error)."""
    conn = get_db()
    try:
        cur = conn.execute(sql, args)
        rows = cur.fetchall()
        conn.close()
        return rows
    except sqlite3.Error:
        conn.close()
        return None


# ============================================================
# HOME
# ============================================================
@app.route("/")
def index():
    students = query("SELECT COUNT(*) AS c FROM students")
    courses_total = query("SELECT COUNT(*) AS c FROM courses")
    faculty_total = query("SELECT COUNT(*) AS c FROM faculty")
    courses = query("SELECT * FROM courses ORDER BY name LIMIT 6") or []
    faculty = query("SELECT * FROM faculty LIMIT 4") or []

    notices = [
        "Admissions open for the Academic Year 2026-27. Apply before 31st March 2026.",
        "TY B.Sc. Computer Science Semester 6 practical examinations from 20th April 2026.",
        "Annual Tech Fest 'CodeSprint 2026' on campus on 15th May 2026 - register now.",
        "New Cyber Security and Data Science elective courses introduced this semester.",
    ]

    return render_template(
        "index.html",
        active="index",
        count_students=students[0]["c"] if students else 0,
        count_courses=courses_total[0]["c"] if courses_total else 0,
        count_faculty=faculty_total[0]["c"] if faculty_total else 0,
        courses=courses,
        faculty=faculty,
        notices=notices,
    )


# ============================================================
# ABOUT / COURSES / FACULTY / CONTACT
# ============================================================
@app.route("/about")
def about():
    return render_template("about.html", active="about")


@app.route("/courses")
def courses():
    rows = query("SELECT * FROM courses ORDER BY name") or []
    return render_template("courses.html", active="courses", courses=rows)


@app.route("/faculty")
def faculty():
    rows = query("SELECT * FROM faculty ORDER BY name") or []
    return render_template("faculty.html", active="faculty", faculty=rows)


@app.route("/contact", methods=["GET", "POST"])
def contact():
    sent = request.method == "POST"
    sent_name = request.form.get("name", "") if sent else ""
    return render_template(
        "contact.html", active="contact", sent=sent, sent_name=sent_name
    )


# ============================================================
# STUDENT LOGIN (INTENTIONALLY VULNERABLE)
# ============================================================
@app.route("/student-login", methods=["GET", "POST"])
def student_login():
    error = None
    executed_sql = None

    if request.method == "POST":
        username = request.form.get("username", "")
        password = request.form.get("password", "")

        # INTENTIONALLY VULNERABLE: raw SQL concatenation - no prepared
        # statements, no escaping, no parameterisation. Classroom only.
        sql = (
            "SELECT * FROM students WHERE username = '"
            + username
            + "' AND password = '"
            + password
            + "'"
        )
        executed_sql = sql

        rows = query(sql)
        if rows:
            student = rows[0]
            session["student_id"] = student["id"]
            session["student_name"] = student["name"]
            session["student_roll"] = student["roll_no"]
            session["student_email"] = student["email"]
            return redirect(url_for("dashboard"))
        error = "Invalid username or password."

    return render_template(
        "student_login.html",
        active="student_login",
        error=error,
        executed_sql=executed_sql,
    )


# ============================================================
# ADMIN LOGIN (INTENTIONALLY VULNERABLE)
# ============================================================
@app.route("/admin-login", methods=["GET", "POST"])
def admin_login():
    error = None
    executed_sql = None

    if request.method == "POST":
        username = request.form.get("username", "")
        password = request.form.get("password", "")

        # INTENTIONALLY VULNERABLE: raw SQL concatenation.
        sql = (
            "SELECT * FROM admins WHERE username = '"
            + username
            + "' AND password = '"
            + password
            + "'"
        )
        executed_sql = sql

        rows = query(sql)
        if rows:
            admin = rows[0]
            session["admin_id"] = admin["id"]
            session["admin_name"] = admin["full_name"]
            session["admin_role"] = admin["role"]
            return redirect(url_for("dashboard"))
        error = "Invalid admin credentials."

    return render_template(
        "admin_login.html",
        active="admin_login",
        error=error,
        executed_sql=executed_sql,
    )


# ============================================================
# STUDENT SEARCH (INTENTIONALLY VULNERABLE)
# ============================================================
@app.route("/student-search", methods=["GET", "POST"])
def student_search():
    student = None
    executed_sql = None
    search_done = False

    if request.method == "POST":
        roll = request.form.get("roll_no", "")
        search_done = True

        # INTENTIONALLY VULNERABLE: raw SQL concatenation in WHERE clause.
        sql = (
            "SELECT s.*, c.name AS course_name FROM students s "
            "LEFT JOIN courses c ON s.course_id = c.id "
            "WHERE s.roll_no = '" + roll + "'"
        )
        executed_sql = sql

        rows = query(sql)
        if rows:
            student = rows[0]

    return render_template(
        "student_search.html",
        active="student_search",
        student=student,
        executed_sql=executed_sql,
        search_done=search_done,
    )


# ============================================================
# RESULT PORTAL (INTENTIONALLY VULNERABLE)
# ============================================================
@app.route("/results", methods=["GET", "POST"])
def results():
    rows = None
    executed_sql = None
    search_done = False
    summary = None

    if request.method == "POST":
        roll = request.form.get("roll_no", "")
        search_done = True

        # INTENTIONALLY VULNERABLE: raw SQL concatenation in WHERE clause.
        sql = (
            "SELECT * FROM results WHERE roll_no = '"
            + roll
            + "' ORDER BY semester DESC, subject ASC"
        )
        executed_sql = sql

        rows = query(sql)
        if rows:
            total_marks = sum(r["total"] for r in rows)
            total_obtained = sum(r["marks"] for r in rows)
            overall_pct = round((total_obtained / total_marks) * 100, 2) if total_marks else 0
            summary = {
                "subjects": len(rows),
                "obtained": total_obtained,
                "max": total_marks,
                "percentage": overall_pct,
                "grade": grade_for(overall_pct),
            }

    return render_template(
        "results.html",
        active="results",
        results=rows or [],
        executed_sql=executed_sql,
        search_done=search_done,
        summary=summary,
    )


# ============================================================
# DASHBOARD (student or admin)
# ============================================================
@app.route("/dashboard")
def dashboard():
    is_student = "student_id" in session
    is_admin = "admin_id" in session

    if not is_student and not is_admin:
        return redirect(url_for("index"))

    student_info = None
    my_results = []

    if is_student:
        roll = session["student_roll"]
        # Vulnerable-style query (roll is server-side, kept consistent with the lab).
        sql = (
            "SELECT s.*, c.name AS course_name FROM students s "
            "LEFT JOIN courses c ON s.course_id = c.id "
            "WHERE s.roll_no = '" + roll + "'"
        )
        rows = query(sql)
        if rows:
            student_info = rows[0]
        my_results = query(
            "SELECT * FROM results WHERE roll_no = '"
            + roll
            + "' ORDER BY semester DESC, subject ASC"
        ) or []

    students_list = courses_list = faculty_list = []
    if is_admin:
        students_list = query(
            "SELECT s.*, c.name AS course_name FROM students s "
            "LEFT JOIN courses c ON s.course_id = c.id ORDER BY s.roll_no"
        ) or []
        courses_list = query("SELECT * FROM courses ORDER BY name") or []
        faculty_list = query("SELECT * FROM faculty ORDER BY name") or []

    return render_template(
        "dashboard.html",
        active="dashboard",
        is_student=is_student,
        is_admin=is_admin,
        student_info=student_info,
        my_results=my_results,
        students_list=students_list,
        courses_list=courses_list,
        faculty_list=faculty_list,
    )


# ============================================================
# LOGOUT
# ============================================================
@app.route("/logout")
def logout():
    session.clear()
    return redirect(url_for("index"))


if __name__ == "__main__":
    port = int(os.environ.get("PORT", 5000))
    app.run(host="0.0.0.0", port=port, debug=True)

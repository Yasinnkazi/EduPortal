"""
EduPortal - SQLite database layer.

Educational SQL Injection lab. Intentionally vulnerable - classroom use only.

On Vercel the filesystem is read-only except /tmp, so the SQLite file is
created there and re-seeded on every cold start. Locally it is created in
the project directory. The schema + seed data live in database/eduportal.sql
and are applied by init_db().
"""

import os
import sqlite3

BASE_DIR = os.path.dirname(os.path.abspath(__file__))
SCHEMA_FILE = os.path.join(BASE_DIR, "database", "eduportal.sql")


def db_path():
    """Pick a writable SQLite file location (Vercel vs local)."""
    if os.environ.get("VERCEL"):
        return "/tmp/eduportal.db"
    return os.path.join(BASE_DIR, "database.db")


def get_db():
    conn = sqlite3.connect(db_path())
    conn.row_factory = sqlite3.Row
    return conn


def init_db():
    """Create tables and seed data. Idempotent - safe to call on startup."""
    with open(SCHEMA_FILE, "r", encoding="utf-8") as f:
        schema = f.read()
    conn = get_db()
    try:
        conn.executescript(schema)
        conn.commit()
    finally:
        conn.close()

<?php
/**
 * EduPortal - configuration & database connection
 * Educational SQL Injection lab. Intentionally vulnerable - classroom use only.
 */
session_start();

$DB_HOST = "localhost";
$DB_USER = "root";
$DB_PASS = "";
$DB_NAME = "eduportal";

$conn = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Fail gracefully on malformed queries (e.g. during injection attempts) so the
// lab pages render a friendly "no results" message instead of a fatal error.
mysqli_report(MYSQLI_REPORT_OFF);

function base_url()
{
    return "/EduPortal";
}

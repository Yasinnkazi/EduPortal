<?php
$pageTitle = "Student Login";
require_once "config.php";

$error = "";
$executed_sql = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // INTENTIONALLY VULNERABLE: raw SQL concatenation - no prepared statements,
    // no escaping, no parameterisation. Classroom demonstration only.
    $sql = "SELECT * FROM students WHERE username = '" . $username . "' AND password = '" . $password . "'";
    $executed_sql = $sql;

    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $student = mysqli_fetch_assoc($result);
        $_SESSION["student_id"] = $student["id"];
        $_SESSION["student_name"] = $student["name"];
        $_SESSION["student_roll"] = $student["roll_no"];
        $_SESSION["student_email"] = $student["email"];
        header("Location: dashboard.php");
        exit;
    }
    $error = "Invalid username or password.";
}
require_once "includes/header.php";
?>
<div class="auth-wrapper">
    <div class="card auth-card p-4">
        <div class="text-center mb-4">
            <img src="assets/images/logo.svg" width="56" height="56" alt="EduPortal">
            <h4 class="fw-bold mt-3 mb-1">Student Login</h4>
            <p class="text-muted small mb-0">Access your EduPortal student dashboard</p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger alert-auto-dismiss py-2 small"><i class="bi bi-x-circle me-1"></i><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="student-login.php" autocomplete="off">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" placeholder="e.g. aarav01" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn btn-primary w-100"><i class="bi bi-box-arrow-in-right me-1"></i>Login</button>
        </form>

        <div class="text-center small text-muted mt-3">
            Demo student: <code>aarav01</code> / <code>student123</code>
        </div>

        <?php if ($executed_sql): ?>
            <div class="mt-3">
                <div class="small text-muted mb-1"><i class="bi bi-code-slash me-1"></i>Query executed</div>
                <div class="sql-box"><?php echo htmlspecialchars($executed_sql); ?></div>
            </div>
        <?php endif; ?>

        <div class="mt-3 p-2 rounded bg-warning bg-opacity-10 small text-dark border border-warning-subtle">
            <i class="bi bi-exclamation-triangle-fill me-1"></i>Educational security lab - intentionally vulnerable login.
        </div>
    </div>
</div>
<?php require_once "includes/footer.php"; ?>

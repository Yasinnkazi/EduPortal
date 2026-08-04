<?php
$pageTitle = "Student Search";
require_once "config.php";

$student = null;
$executed_sql = "";
$search_done = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $roll = $_POST["roll_no"];
    $search_done = true;

    // INTENTIONALLY VULNERABLE: raw SQL concatenation in WHERE clause.
    $sql = "SELECT s.*, c.name AS course_name FROM students s LEFT JOIN courses c ON s.course_id = c.id WHERE s.roll_no = '" . $roll . "'";
    $executed_sql = $sql;

    $result = mysqli_query($conn, $sql);
    if ($result) {
        $student = mysqli_fetch_assoc($result);
    }
}
require_once "includes/header.php";
?>
<h4 class="section-title mb-1"><i class="bi bi-person-lines-fill text-primary me-2"></i>Student Search</h4>
<p class="section-sub mb-4">Search any enrolled student by Roll Number.</p>

<div class="row g-4">
    <div class="col-lg-5">
        <div class="card p-4">
            <form method="POST" action="student-search.php">
                <label class="form-label">Roll Number</label>
                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="bi bi-upc-scan"></i></span>
                    <input type="text" name="roll_no" class="form-control" placeholder="e.g. BCS2026-001" value="<?php echo isset($_POST['roll_no']) ? htmlspecialchars($_POST['roll_no']) : ''; ?>" required>
                </div>
                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search me-1"></i>Search Student</button>
            </form>

            <div class="mt-3 p-2 rounded bg-warning bg-opacity-10 small text-dark border border-warning-subtle">
                <i class="bi bi-exclamation-triangle-fill me-1"></i>Educational security lab - intentionally vulnerable search.
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <?php if ($executed_sql): ?>
            <div class="mb-3">
                <div class="small text-muted mb-1"><i class="bi bi-code-slash me-1"></i>Query executed</div>
                <div class="sql-box"><?php echo htmlspecialchars($executed_sql); ?></div>
            </div>
        <?php endif; ?>

        <?php if ($search_done && $student): ?>
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-circle me-3"><?php echo strtoupper(substr($student['name'], 0, 1)); ?></div>
                        <div>
                            <h5 class="fw-bold mb-0"><?php echo htmlspecialchars($student['name']); ?></h5>
                            <span class="badge text-bg-primary"><?php echo htmlspecialchars($student['roll_no']); ?></span>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-sm-6"><div class="small text-muted">Course</div><div class="fw-semibold"><?php echo htmlspecialchars($student['course_name'] ?? "N/A"); ?></div></div>
                        <div class="col-sm-6"><div class="small text-muted">Semester</div><div class="fw-semibold">Semester <?php echo (int)$student['semester']; ?></div></div>
                        <div class="col-sm-6"><div class="small text-muted">Email</div><div class="fw-semibold"><?php echo htmlspecialchars($student['email']); ?></div></div>
                        <div class="col-sm-6"><div class="small text-muted">Phone</div><div class="fw-semibold"><?php echo htmlspecialchars($student['phone']); ?></div></div>
                        <div class="col-sm-6"><div class="small text-muted">City</div><div class="fw-semibold"><?php echo htmlspecialchars($student['city']); ?></div></div>
                        <div class="col-sm-6"><div class="small text-muted">Status</div><div class="fw-semibold text-success"><i class="bi bi-check-circle me-1"></i>Enrolled</div></div>
                    </div>
                </div>
            </div>
        <?php elseif ($search_done): ?>
            <div class="alert alert-warning"><i class="bi bi-exclamation-triangle me-1"></i>No student found with that roll number.</div>
        <?php endif; ?>
    </div>
</div>

<?php require_once "includes/footer.php"; ?>

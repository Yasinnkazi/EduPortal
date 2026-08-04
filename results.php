<?php
$pageTitle = "Result Portal";
require_once "config.php";

$results = null;
$executed_sql = "";
$search_done = false;
$summary = null;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $roll = $_POST["roll_no"];
    $search_done = true;

    // INTENTIONALLY VULNERABLE: raw SQL concatenation in WHERE clause.
    $sql = "SELECT * FROM results WHERE roll_no = '" . $roll . "' ORDER BY semester DESC, subject ASC";
    $executed_sql = $sql;

    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $results = array();
        $total_marks = 0;
        $total_obtained = 0;
        $count = 0;
        while ($row = mysqli_fetch_assoc($result)) {
            $results[] = $row;
            $total_marks += (int)$row['total'];
            $total_obtained += (int)$row['marks'];
            $count++;
        }
        $overall_pct = $total_marks > 0 ? round(($total_obtained / $total_marks) * 100, 2) : 0;
        $grade = "F";
        if ($overall_pct >= 90) { $grade = "A+"; }
        elseif ($overall_pct >= 80) { $grade = "A"; }
        elseif ($overall_pct >= 70) { $grade = "B+"; }
        elseif ($overall_pct >= 60) { $grade = "B"; }
        elseif ($overall_pct >= 50) { $grade = "C"; }
        elseif ($overall_pct >= 40) { $grade = "D"; }
        $summary = array(
            "subjects" => $count,
            "obtained" => $total_obtained,
            "max" => $total_marks,
            "percentage" => $overall_pct,
            "grade" => $grade,
        );
    }
}
require_once "includes/header.php";
?>
<h4 class="section-title mb-1"><i class="bi bi-award text-primary me-2"></i>Result Portal</h4>
<p class="section-sub mb-4">Check your examination results by Roll Number.</p>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card p-4">
            <form method="POST" action="results.php">
                <label class="form-label">Roll Number</label>
                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="bi bi-upc-scan"></i></span>
                    <input type="text" name="roll_no" class="form-control" placeholder="e.g. BCS2026-001" value="<?php echo isset($_POST['roll_no']) ? htmlspecialchars($_POST['roll_no']) : ''; ?>" required>
                </div>
                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search me-1"></i>View Result</button>
            </form>

            <div class="mt-3 p-2 rounded bg-warning bg-opacity-10 small text-dark border border-warning-subtle">
                <i class="bi bi-exclamation-triangle-fill me-1"></i>Educational security lab - intentionally vulnerable search.
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <?php if ($executed_sql): ?>
            <div class="mb-3">
                <div class="small text-muted mb-1"><i class="bi bi-code-slash me-1"></i>Query executed</div>
                <div class="sql-box"><?php echo htmlspecialchars($executed_sql); ?></div>
            </div>
        <?php endif; ?>

        <?php if ($summary): ?>
            <div class="row g-3 mb-3">
                <div class="col-3"><div class="stat-tile text-center"><div class="stat-num"><?php echo $summary['subjects']; ?></div><div class="text-muted small">Subjects</div></div></div>
                <div class="col-3"><div class="stat-tile text-center"><div class="stat-num"><?php echo $summary['obtained']; ?>/<small><?php echo $summary['max']; ?></small></div><div class="text-muted small">Marks</div></div></div>
                <div class="col-3"><div class="stat-tile text-center"><div class="stat-num"><?php echo $summary['percentage']; ?>%</div><div class="text-muted small">Percentage</div></div></div>
                <div class="col-3"><div class="stat-tile text-center"><div class="stat-num text-success"><?php echo $summary['grade']; ?></div><div class="text-muted small">Grade</div></div></div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr><th>#</th><th>Subject</th><th>Exam</th><th>Semester</th><th>Marks</th><th>Percentage</th><th>Grade</th></tr>
                            </thead>
                            <tbody>
                                <?php $i = 1; foreach ($results as $r): ?>
                                <tr>
                                    <td><?php echo $i++; ?></td>
                                    <td class="fw-semibold"><?php echo htmlspecialchars($r['subject']); ?></td>
                                    <td class="small"><?php echo htmlspecialchars($r['exam_type']); ?></td>
                                    <td><?php echo (int)$r['semester']; ?></td>
                                    <td><?php echo (int)$r['marks']; ?> / <?php echo (int)$r['total']; ?></td>
                                    <td><?php echo number_format((float)$r['percentage'], 2); ?>%</td>
                                    <td><span class="badge text-bg-success"><?php echo htmlspecialchars($r['grade']); ?></span></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php elseif ($search_done): ?>
            <div class="alert alert-warning"><i class="bi bi-exclamation-triangle me-1"></i>No results published for that roll number.</div>
        <?php endif; ?>
    </div>
</div>

<?php require_once "includes/footer.php"; ?>

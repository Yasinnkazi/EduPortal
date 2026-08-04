<?php
$pageTitle = "Dashboard";
require_once "config.php";

$is_student = isset($_SESSION["student_id"]);
$is_admin = isset($_SESSION["admin_id"]);

if (!$is_student && !$is_admin) {
    header("Location: index.php");
    exit;
}

$student_info = null;
if ($is_student) {
    $roll = $_SESSION["student_roll"];
    // Vulnerable-style query (roll is server-side, but kept consistent with the lab).
    $sql = "SELECT s.*, c.name AS course_name FROM students s LEFT JOIN courses c ON s.course_id = c.id WHERE s.roll_no = '" . $roll . "'";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        $student_info = mysqli_fetch_assoc($result);
    }
    $res_q = "SELECT * FROM results WHERE roll_no = '" . $roll . "' ORDER BY semester DESC, subject ASC";
    $res_res = mysqli_query($conn, $res_q);
}

$students_list = $courses_list = $faculty_list = null;
if ($is_admin) {
    $students_list = mysqli_query($conn, "SELECT s.*, c.name AS course_name FROM students s LEFT JOIN courses c ON s.course_id = c.id ORDER BY s.roll_no");
    $courses_list = mysqli_query($conn, "SELECT * FROM courses ORDER BY name");
    $faculty_list = mysqli_query($conn, "SELECT * FROM faculty ORDER BY name");
    $counts = array(
        "students" => mysqli_num_rows($students_list),
        "courses" => mysqli_num_rows($courses_list),
        "faculty" => mysqli_num_rows($faculty_list),
    );
}
require_once "includes/header.php";
?>

<?php if ($is_admin): ?>
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="section-title mb-0"><i class="bi bi-shield-lock text-primary me-2"></i>Admin Dashboard</h4>
            <p class="section-sub mb-0">Welcome back, <?php echo htmlspecialchars($_SESSION["admin_name"]); ?> (<?php echo htmlspecialchars($_SESSION["admin_role"]); ?>)</p>
        </div>
        <a href="logout.php" class="btn btn-outline-danger btn-sm">Logout</a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4"><div class="stat-tile"><div class="stat-num"><?php echo $counts["students"]; ?></div><div class="text-muted small"><i class="bi bi-people me-1"></i>Total Students</div></div></div>
        <div class="col-md-4"><div class="stat-tile"><div class="stat-num"><?php echo $counts["courses"]; ?></div><div class="text-muted small"><i class="bi bi-journal-bookmark me-1"></i>Total Courses</div></div></div>
        <div class="col-md-4"><div class="stat-tile"><div class="stat-num"><?php echo $counts["faculty"]; ?></div><div class="text-muted small"><i class="bi bi-person-badge me-1"></i>Faculty Members</div></div></div>
    </div>

    <ul class="nav nav-pills mb-3" id="admTabs" role="tablist">
        <li class="nav-item"><button class="nav-link active" data-bs-toggle="pill" data-bs-target="#tabStudents" type="button"><i class="bi bi-people me-1"></i>Student List</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#tabCourses" type="button"><i class="bi bi-journal-bookmark me-1"></i>Course List</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#tabFaculty" type="button"><i class="bi bi-person-badge me-1"></i>Faculty List</button></li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane fade show active" id="tabStudents">
            <div class="card"><div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead><tr><th>Roll No</th><th>Name</th><th>Course</th><th>Semester</th><th>Email</th><th>City</th></tr></thead>
                        <tbody>
                        <?php while ($s = mysqli_fetch_assoc($students_list)): ?>
                            <tr>
                                <td class="fw-semibold"><?php echo htmlspecialchars($s['roll_no']); ?></td>
                                <td><?php echo htmlspecialchars($s['name']); ?></td>
                                <td><?php echo htmlspecialchars($s['course_name'] ?? "N/A"); ?></td>
                                <td><?php echo (int)$s['semester']; ?></td>
                                <td class="small"><?php echo htmlspecialchars($s['email']); ?></td>
                                <td><?php echo htmlspecialchars($s['city']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div></div>
        </div>

        <div class="tab-pane fade" id="tabCourses">
            <div class="row g-3">
            <?php while ($c = mysqli_fetch_assoc($courses_list)): ?>
                <div class="col-md-4 col-sm-6">
                    <div class="card card-hover h-100"><div class="card-body">
                        <span class="badge text-bg-primary mb-2"><?php echo htmlspecialchars($c['code']); ?></span>
                        <h6 class="fw-bold mb-1"><?php echo htmlspecialchars($c['name']); ?></h6>
                        <div class="small text-muted"><?php echo htmlspecialchars($c['department']); ?> &middot; <?php echo htmlspecialchars($c['duration']); ?></div>
                    </div></div>
                </div>
            <?php endwhile; ?>
            </div>
        </div>

        <div class="tab-pane fade" id="tabFaculty">
            <div class="card"><div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead><tr><th>Name</th><th>Designation</th><th>Department</th><th>Qualification</th><th>Email</th></tr></thead>
                        <tbody>
                        <?php while ($f = mysqli_fetch_assoc($faculty_list)): ?>
                            <tr>
                                <td class="fw-semibold"><?php echo htmlspecialchars($f['name']); ?></td>
                                <td><?php echo htmlspecialchars($f['designation']); ?></td>
                                <td><?php echo htmlspecialchars($f['department']); ?></td>
                                <td class="small"><?php echo htmlspecialchars($f['qualification']); ?></td>
                                <td class="small"><?php echo htmlspecialchars($f['email']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div></div>
        </div>
    </div>

<?php elseif ($is_student && $student_info): ?>
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="section-title mb-0"><i class="bi bi-person-badge text-primary me-2"></i>Student Dashboard</h4>
            <p class="section-sub mb-0">Welcome, <?php echo htmlspecialchars($student_info['name']); ?></p>
        </div>
        <a href="logout.php" class="btn btn-outline-danger btn-sm">Logout</a>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="d-flex align-items-center">
                        <div class="avatar-circle me-3"><?php echo strtoupper(substr($student_info['name'], 0, 1)); ?></div>
                        <div>
                            <h5 class="fw-bold mb-0"><?php echo htmlspecialchars($student_info['name']); ?></h5>
                            <span class="badge text-bg-primary"><?php echo htmlspecialchars($student_info['roll_no']); ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row g-2 small">
                        <div class="col-6"><span class="text-muted">Course</span><br><b><?php echo htmlspecialchars($student_info['course_name'] ?? "N/A"); ?></b></div>
                        <div class="col-6"><span class="text-muted">Semester</span><br><b><?php echo (int)$student_info['semester']; ?></b></div>
                        <div class="col-6"><span class="text-muted">Email</span><br><b><?php echo htmlspecialchars($student_info['email']); ?></b></div>
                        <div class="col-6"><span class="text-muted">Phone</span><br><b><?php echo htmlspecialchars($student_info['phone']); ?></b></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-white fw-semibold"><i class="bi bi-award text-primary me-2"></i>My Recent Results</div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead><tr><th>Subject</th><th>Exam</th><th>Semester</th><th>Marks</th><th>Percentage</th><th>Grade</th></tr></thead>
                    <tbody>
                    <?php $shown = 0; while ($res_res && $r = mysqli_fetch_assoc($res_res)): $shown++; ?>
                        <tr>
                            <td class="fw-semibold"><?php echo htmlspecialchars($r['subject']); ?></td>
                            <td class="small"><?php echo htmlspecialchars($r['exam_type']); ?></td>
                            <td><?php echo (int)$r['semester']; ?></td>
                            <td><?php echo (int)$r['marks']; ?> / <?php echo (int)$r['total']; ?></td>
                            <td><?php echo number_format((float)$r['percentage'], 2); ?>%</td>
                            <td><span class="badge text-bg-success"><?php echo htmlspecialchars($r['grade']); ?></span></td>
                        </tr>
                    <?php endwhile; ?>
                    <?php if ($shown == 0): ?>
                        <tr><td colspan="6" class="text-muted text-center py-4">No results published yet.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php require_once "includes/footer.php"; ?>

<?php
$pageTitle = "Home";
require_once "config.php";
require_once "includes/header.php";

$count_students = 0;
$count_courses = 0;
$count_faculty = 0;
$res_students = mysqli_query($conn, "SELECT COUNT(*) AS c FROM students");
$res_courses = mysqli_query($conn, "SELECT COUNT(*) AS c FROM courses");
$res_faculty = mysqli_query($conn, "SELECT COUNT(*) AS c FROM faculty");
if ($res_students) { $count_students = mysqli_fetch_assoc($res_students)['c']; }
if ($res_courses) { $count_courses = mysqli_fetch_assoc($res_courses)['c']; }
if ($res_faculty) { $res_faculty = mysqli_fetch_assoc($res_faculty); $count_faculty = $res_faculty['c']; }

$courses = mysqli_query($conn, "SELECT * FROM courses ORDER BY name LIMIT 6");
$faculty = mysqli_query($conn, "SELECT * FROM faculty LIMIT 4");

$notices = array(
    "Admissions open for the Academic Year 2026-27. Apply before 31st March 2026.",
    "TY B.Sc. Computer Science Semester 6 practical examinations from 20th April 2026.",
    "Annual Tech Fest 'CodeSprint 2026' on campus on 15th May 2026 - register now.",
    "New Cyber Security and Data Science elective courses introduced this semester.",
);
?>

<!-- Hero -->
<section class="hero mb-4">
    <div class="row align-items-center position-relative">
        <div class="col-lg-7">
            <span class="badge badge-lab rounded-pill mb-3"><i class="bi bi-shield-exclamation me-1"></i>Security Lab Environment</span>
            <h1 class="display-6 mb-3">EduPortal</h1>
            <p class="lead mb-4">A realistic Educational Student Management Portal. Manage students, courses, faculty and results — the way a modern university ERP does.</p>
            <div class="d-flex flex-wrap gap-2">
                <a href="student-search.php" class="btn btn-light"><i class="bi bi-search me-1"></i>Search Student</a>
                <a href="results.php" class="btn btn-outline-light"><i class="bi bi-award me-1"></i>Result Portal</a>
                <a href="student-login.php" class="btn btn-outline-light"><i class="bi bi-box-arrow-in-right me-1"></i>Student Login</a>
            </div>
        </div>
        <div class="col-lg-5 d-none d-lg-block">
            <img src="assets/images/logo.svg" alt="EduPortal" width="220" class="float-end opacity-75">
        </div>
    </div>
</section>

<!-- Disclaimer -->
<div class="disclaimer-banner p-3 mb-4">
    <h6 class="mb-1"><i class="bi bi-exclamation-triangle-fill me-2"></i>WARNING</h6>
    <p class="mb-0 small">This application is intentionally vulnerable and is designed ONLY for educational demonstrations of SQL Injection in a controlled laboratory environment. It must never be deployed on a public server.</p>
</div>

<!-- Stats -->
<section class="row g-3 mb-4">
    <div class="col-md-3 col-6">
        <div class="stat-tile"><div class="stat-num"><?php echo $count_students; ?></div><div class="text-muted small">Students</div></div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-tile"><div class="stat-num"><?php echo $count_courses; ?></div><div class="text-muted small">Courses</div></div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-tile"><div class="stat-num"><?php echo $count_faculty; ?></div><div class="text-muted small">Faculty Members</div></div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-tile"><div class="stat-num">22</div><div class="text-muted small">Departments</div></div>
    </div>
</section>

<!-- Notices + quick access -->
<section class="row g-4 mb-4">
    <div class="col-lg-5">
        <h5 class="section-title mb-3"><i class="bi bi-megaphone text-primary me-2"></i>Latest Notices</h5>
        <?php foreach ($notices as $n): ?>
            <div class="notice-item mb-2"><i class="bi bi-bell text-primary me-2"></i><?php echo $n; ?></div>
        <?php endforeach; ?>
    </div>
    <div class="col-lg-7">
        <h5 class="section-title mb-3"><i class="bi bi-speedometer2 text-primary me-2"></i>Quick Access</h5>
        <div class="row g-3">
            <div class="col-sm-6">
                <a href="student-search.php" class="card card-hover h-100 text-decoration-none text-dark">
                    <div class="card-body">
                        <div class="card-icon bg-primary-subtle text-primary mb-2"><i class="bi bi-person-lines-fill"></i></div>
                        <h6 class="mb-1 fw-bold">Student Search</h6>
                        <p class="text-muted small mb-0">Look up any student by Roll Number.</p>
                    </div>
                </a>
            </div>
            <div class="col-sm-6">
                <a href="results.php" class="card card-hover h-100 text-decoration-none text-dark">
                    <div class="card-body">
                        <div class="card-icon bg-success-subtle text-success mb-2"><i class="bi bi-award"></i></div>
                        <h6 class="mb-1 fw-bold">Result Portal</h6>
                        <p class="text-muted small mb-0">View semester marks, percentage and grade.</p>
                    </div>
                </a>
            </div>
            <div class="col-sm-6">
                <a href="student-login.php" class="card card-hover h-100 text-decoration-none text-dark">
                    <div class="card-body">
                        <div class="card-icon bg-info-subtle text-info mb-2"><i class="bi bi-person-badge"></i></div>
                        <h6 class="mb-1 fw-bold">Student Login</h6>
                        <p class="text-muted small mb-0">Sign in to your student dashboard.</p>
                    </div>
                </a>
            </div>
            <div class="col-sm-6">
                <a href="admin-login.php" class="card card-hover h-100 text-decoration-none text-dark">
                    <div class="card-body">
                        <div class="card-icon bg-warning-subtle text-warning mb-2"><i class="bi bi-shield-lock"></i></div>
                        <h6 class="mb-1 fw-bold">Admin Login</h6>
                        <p class="text-muted small mb-0">Administrator control panel access.</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Courses -->
<section class="mb-4">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h5 class="section-title mb-0"><i class="bi bi-journal-bookmark text-primary me-2"></i>Our Courses</h5>
        <a href="courses.php" class="btn btn-sm btn-outline-primary">View All <i class="bi bi-arrow-right"></i></a>
    </div>
    <div class="row g-3">
        <?php while ($c = mysqli_fetch_assoc($courses)): ?>
        <div class="col-md-4 col-sm-6">
            <div class="card card-hover h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="badge text-bg-primary"><?php echo htmlspecialchars($c['code']); ?></span>
                        <span class="text-muted small"><i class="bi bi-clock me-1"></i><?php echo htmlspecialchars($c['duration']); ?></span>
                    </div>
                    <h6 class="fw-bold mb-1"><?php echo htmlspecialchars($c['name']); ?></h6>
                    <p class="text-muted small mb-3"><?php echo htmlspecialchars($c['description']); ?></p>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="small text-muted"><i class="bi bi-people me-1"></i><?php echo (int)$c['seats']; ?> seats</span>
                        <span class="fw-semibold text-primary">Rs. <?php echo number_format((float)$c['fee']); ?></span>
                    </div>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</section>

<!-- Faculty preview -->
<section>
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h5 class="section-title mb-0"><i class="bi bi-people text-primary me-2"></i>Our Faculty</h5>
        <a href="faculty.php" class="btn btn-sm btn-outline-primary">View All <i class="bi bi-arrow-right"></i></a>
    </div>
    <div class="row g-3">
        <?php while ($f = mysqli_fetch_assoc($faculty)): ?>
        <div class="col-md-3 col-sm-6">
            <div class="card card-hover h-100">
                <div class="card-body text-center">
                    <div class="avatar-circle mx-auto mb-2"><?php echo strtoupper(substr($f['name'], 0, 1)); ?></div>
                    <h6 class="fw-bold mb-0"><?php echo htmlspecialchars($f['name']); ?></h6>
                    <div class="small text-primary fw-semibold"><?php echo htmlspecialchars($f['designation']); ?></div>
                    <div class="small text-muted"><?php echo htmlspecialchars($f['department']); ?></div>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</section>

<?php require_once "includes/footer.php"; ?>

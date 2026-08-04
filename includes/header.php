<?php
$active = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo isset($pageTitle) ? $pageTitle . " - " : ""; ?>EduPortal</title>
    <link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/vendor/bootstrap-icons/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top shadow-sm border-bottom">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="index.php">
            <img src="assets/images/logo.svg" alt="EduPortal" width="36" height="36" class="me-2">
            <span class="fw-bold brand-text">Edu<span class="text-primary">Portal</span></span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav me-auto ms-3">
                <li class="nav-item"><a class="nav-link <?php echo $active == 'index.php' ? 'active' : ''; ?>" href="index.php"><i class="bi bi-house-door me-1"></i>Home</a></li>
                <li class="nav-item"><a class="nav-link <?php echo $active == 'about.php' ? 'active' : ''; ?>" href="about.php"><i class="bi bi-info-circle me-1"></i>About</a></li>
                <li class="nav-item"><a class="nav-link <?php echo $active == 'courses.php' ? 'active' : ''; ?>" href="courses.php"><i class="bi bi-journal-bookmark me-1"></i>Courses</a></li>
                <li class="nav-item"><a class="nav-link <?php echo $active == 'faculty.php' ? 'active' : ''; ?>" href="faculty.php"><i class="bi bi-people me-1"></i>Faculty</a></li>
                <li class="nav-item"><a class="nav-link <?php echo $active == 'student-search.php' ? 'active' : ''; ?>" href="student-search.php"><i class="bi bi-search me-1"></i>Student Search</a></li>
                <li class="nav-item"><a class="nav-link <?php echo $active == 'results.php' ? 'active' : ''; ?>" href="results.php"><i class="bi bi-award me-1"></i>Result Portal</a></li>
                <li class="nav-item"><a class="nav-link <?php echo $active == 'contact.php' ? 'active' : ''; ?>" href="contact.php"><i class="bi bi-envelope me-1"></i>Contact</a></li>
            </ul>
            <div class="d-flex align-items-center gap-2">
                <?php if (isset($_SESSION['student_id']) || isset($_SESSION['admin_id'])): ?>
                    <span class="me-2 text-muted small"><i class="bi bi-person-circle me-1"></i><?php echo htmlspecialchars($_SESSION['student_name'] ?? $_SESSION['admin_name']); ?></span>
                    <a href="dashboard.php" class="btn btn-outline-primary btn-sm">Dashboard</a>
                    <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
                <?php else: ?>
                    <a href="student-login.php" class="btn btn-outline-primary btn-sm"><i class="bi bi-person-badge me-1"></i>Student Login</a>
                    <a href="admin-login.php" class="btn btn-primary btn-sm"><i class="bi bi-shield-lock me-1"></i>Admin Login</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<main class="py-4">
    <div class="container">

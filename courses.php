<?php
$pageTitle = "Courses";
require_once "config.php";

$courses = mysqli_query($conn, "SELECT * FROM courses ORDER BY name");
require_once "includes/header.php";
?>
<h4 class="section-title mb-1"><i class="bi bi-journal-bookmark text-primary me-2"></i>Courses Offered</h4>
<p class="section-sub mb-4">Explore the academic programmes offered by EduPortal.</p>

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
                <div class="small text-muted mb-2"><i class="bi bi-diagram-3 me-1"></i><?php echo htmlspecialchars($c['department']); ?></div>
                <p class="small text-muted mb-3"><?php echo htmlspecialchars($c['description']); ?></p>
                <div class="d-flex justify-content-between align-items-center border-top pt-3">
                    <span class="small text-muted"><i class="bi bi-people me-1"></i><?php echo (int)$c['seats']; ?> seats</span>
                    <span class="fw-semibold text-primary">Rs. <?php echo number_format((float)$c['fee']); ?>/yr</span>
                </div>
            </div>
        </div>
    </div>
    <?php endwhile; ?>
</div>
<?php require_once "includes/footer.php"; ?>

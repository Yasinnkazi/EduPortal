<?php
$pageTitle = "Faculty";
require_once "config.php";

$faculty = mysqli_query($conn, "SELECT * FROM faculty ORDER BY name");
require_once "includes/header.php";
?>
<h4 class="section-title mb-1"><i class="bi bi-people text-primary me-2"></i>Our Faculty</h4>
<p class="section-sub mb-4">Meet the experienced educators behind every programme.</p>

<div class="row g-3">
    <?php while ($f = mysqli_fetch_assoc($faculty)): ?>
    <div class="col-md-4 col-sm-6">
        <div class="card card-hover h-100">
            <div class="card-body text-center">
                <div class="avatar-circle mx-auto mb-3" style="width:64px;height:64px;font-size:1.4rem;"><?php echo strtoupper(substr($f['name'], 0, 1)); ?></div>
                <h6 class="fw-bold mb-0"><?php echo htmlspecialchars($f['name']); ?></h6>
                <div class="small text-primary fw-semibold"><?php echo htmlspecialchars($f['designation']); ?></div>
                <div class="small text-muted"><?php echo htmlspecialchars($f['department']); ?></div>
                <hr>
                <div class="text-start small">
                    <div class="mb-1"><i class="bi bi-mortarboard me-2 text-muted"></i><?php echo htmlspecialchars($f['qualification']); ?></div>
                    <div class="mb-1"><i class="bi bi-envelope me-2 text-muted"></i><?php echo htmlspecialchars($f['email']); ?></div>
                    <div><i class="bi bi-telephone me-2 text-muted"></i><?php echo htmlspecialchars($f['phone']); ?></div>
                </div>
            </div>
        </div>
    </div>
    <?php endwhile; ?>
</div>
<?php require_once "includes/footer.php"; ?>

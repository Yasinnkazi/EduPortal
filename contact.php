<?php
$pageTitle = "Contact";
require_once "config.php";
require_once "includes/header.php";

$sent = isset($_POST["name"]);
?>
<h4 class="section-title mb-1"><i class="bi bi-envelope text-primary me-2"></i>Contact Us</h4>
<p class="section-sub mb-4">We are here to help. Reach out to the EduPortal office.</p>

<div class="row g-4">
    <div class="col-lg-5">
        <div class="card p-4">
            <h6 class="fw-bold mb-3">Get in touch</h6>
            <div class="d-flex align-items-start mb-3">
                <div class="card-icon bg-primary-subtle text-primary me-3"><i class="bi bi-geo-alt"></i></div>
                <div><b>Address</b><br><span class="small text-muted">EduPortal Campus, Education Road,<br>Mumbai, Maharashtra - 400001</span></div>
            </div>
            <div class="d-flex align-items-start mb-3">
                <div class="card-icon bg-success-subtle text-success me-3"><i class="bi bi-telephone"></i></div>
                <div><b>Phone</b><br><span class="small text-muted">+91 98 2001 0000 (Office)<br>+91 98 2001 0001 (Helpdesk)</span></div>
            </div>
            <div class="d-flex align-items-start">
                <div class="card-icon bg-info-subtle text-info me-3"><i class="bi bi-envelope"></i></div>
                <div><b>Email</b><br><span class="small text-muted">info@eduportal.ac.in<br>support@eduportal.ac.in</span></div>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card p-4">
            <h6 class="fw-bold mb-3">Send a message</h6>
            <?php if ($sent): ?>
                <div class="alert alert-success alert-auto-dismiss"><i class="bi bi-check-circle me-1"></i>Thank you, <?php echo htmlspecialchars($_POST['name']); ?>! Your message has been recorded.</div>
            <?php endif; ?>
            <form method="POST" action="contact.php">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Your Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Subject</label>
                        <input type="text" name="subject" class="form-control" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Message</label>
                        <textarea name="message" class="form-control" rows="4" required></textarea>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-send me-1"></i>Send Message</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php require_once "includes/footer.php"; ?>

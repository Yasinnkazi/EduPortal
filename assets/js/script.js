/* EduPortal - small client-side helpers (non-security relevant) */
document.addEventListener("DOMContentLoaded", function () {
    // Auto-dismiss Bootstrap alerts after 4s
    document.querySelectorAll(".alert-auto-dismiss").forEach(function (el) {
        setTimeout(function () {
            var alert = bootstrap.Alert.getOrCreateInstance(el);
            if (alert) alert.close();
        }, 4000);
    });

    // Live result percentage preview (Result Portal)
    var percentInputs = document.querySelectorAll("[data-pct-preview]");
    percentInputs.forEach(function (input) {
        input.addEventListener("input", function () {
            var target = document.querySelector(this.dataset.pctPreview);
            if (target) {
                var marks = parseFloat(this.value) || 0;
                var total = parseFloat(this.dataset.total) || 100;
                var pct = (marks / total) * 100;
                target.textContent = pct.toFixed(2) + "%";
            }
        });
    });
});

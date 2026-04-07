<!-- Footer -->
<footer class="admin-footer text-center">
    <p>©
        <?php echo date("Y"); ?> Skillify Admin Panel
    </p>
</footer>

</div>
</div>

<script>
    function toggleSidebar() {
        document.querySelector('.admin-sidebar').classList.toggle('active');
    }
</script>

<!-- Bootstrap Validation Script -->
<script>
    (() => {
        'use strict';
        const forms = document.querySelectorAll('.needs-validation');

        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    })();
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
<footer class="skillify-footer mt-5">
    <div class="container">
        <div class="row gy-4">

            <!-- About -->
            <div class="col-lg-4 col-md-6">
                <div class="footer-brand d-flex align-items-center mb-3">
                    <img src="./images/logo.png" width="35" class="me-2">
                    <span class="footer-logo-text">Skillify</span>
                </div>

                <p class="footer-desc">
                    Skillify is a platform where people can hire professionals or exchange skills.
                    Learn new abilities, collaborate with others, and grow your career together.
                </p>
            </div>

            <!-- Quick Links -->
            <div class="col-lg-2 col-md-6">
                <h5 class="footer-title">Quick Links</h5>

                <ul class="footer-links">
                    <li><a href="home.php">Home</a></li>
                    <li><a href=<?php echo $user['role'] == 'User' ? "jobs.php" : "hire.php"; ?>><?php echo $user['role'] == 'User' ? 'Jobs' : 'Hire'; ?></a></li>
                    <li><a href="skillexchange.php">Skill Exchange</a></li>
                    <li><a href="contact.php">Contact</a></li>
                </ul>
            </div>

            <!-- Services -->
            <div class="col-lg-3 col-md-6">
                <h5 class="footer-title">Services</h5>

                <ul class="footer-links">
                    <li><a href="./browseskill.php?learn=15&teach=0">Web Development</a></li>
                    <li><a href="./browseskill.php?learn=16&teach=0">Graphic Design</a></li>
                    <li><a href="./browseskill.php?learn=17&teach=0">Content Writing</a></li>
                    <li><a href="./browseskill.php?learn=18&teach=0">Digital Marketing</a></li>
                </ul>
            </div>

            <!-- Social -->
            <div class="col-lg-3 col-md-6">
                <h5 class="footer-title">Connect With Us</h5>

                <div class="footer-social">

                    <a href="#">
                        <i class="bi bi-facebook"></i>
                    </a>

                    <a href="#">
                        <i class="bi bi-instagram"></i>
                    </a>

                    <a href="#">
                        <i class="bi bi-twitter-x"></i>
                    </a>

                    <a href="#">
                        <i class="bi bi-linkedin"></i>
                    </a>

                </div>
            </div>

        </div>

        <!-- Bottom -->
        <div class="footer-bottom text-center mt-4">
            <p>
                © <?php echo date("Y"); ?> Skillify. All Rights Reserved.
            </p>
        </div>

    </div>
</footer>

<script src="./public/script.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
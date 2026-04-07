<?php include "./includes/header.php"; ?>

<main>
    <section class="hero-section">
        <div id="skillifyCarousel" class="carousel slide" data-bs-ride="carousel">

            <div class="carousel-inner">

                <!-- Slide 1 -->
                <div class="carousel-item active">
                    <div class="container">
                        <div class="row align-items-center">

                            <div class="col-lg-6 hero-text">
                                <h1>
                                    <?php echo $user['role'] == 'User' ? "Find Your Dream Job" : "Hire The Right Talent"; ?>
                                </h1>

                                <p>
                                    <?php echo $user['role'] == 'User'
                                        ? "Explore opportunities, apply to jobs, and grow your career with Skillify."
                                        : "Connect with skilled candidates and build your dream team easily."; ?>
                                </p>

                                <a href="<?php echo $user['role'] == 'User' ? 'jobs.php' : 'hire.php'; ?>"
                                    class="btn btn-primary-custom">
                                    <?php echo $user['role'] == 'User' ? 'Explore Jobs' : 'Start Hiring'; ?>
                                </a>

                            </div>

                            <div class="col-lg-6 text-center">
                                <img src="./images/career.gif" class="hero-img">
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Slide 2 -->
                <div class="carousel-item">
                    <div class="container">
                        <div class="row align-items-center">

                            <div class="col-lg-6 hero-text">
                                <h1>Exchange Skills & Grow</h1>

                                <p>
                                    Connect with people, exchange knowledge, and learn new skills through
                                    Skillify's community learning platform.
                                </p>

                                <a href="skillexchange.php" class="btn btn-primary-custom">
                                    Explore Skill Exchange
                                </a>

                            </div>

                            <div class="col-lg-6 text-center">
                                <img src="./images/request.gif" class="hero-img">
                            </div>

                        </div>
                    </div>
                </div>

            </div>

        </div>
    </section>

    <section class="home-cards">
        <div class="container">

            <div class="row g-4">

                <!-- Jobs / Hire -->
                <div class="col-md-6">
                    <div class="home-feature-card">

                        <i class="bi bi-briefcase"></i>

                        <h3><?php echo $user['role'] == 'User' ? 'Find Jobs' : 'Hire Talent'; ?></h3>

                        <p>
                            <?php echo $user['role'] == 'User'
                                ? "Search and apply to jobs that match your skills."
                                : "Post opportunities and connect with skilled professionals."; ?>
                        </p>

                        <a href="<?php echo $user['role'] == 'User' ? 'jobs.php' : 'hire.php'; ?>"
                            class="btn btn-primary-custom">
                            <?php echo $user['role'] == 'User' ? 'View Jobs' : 'Start Hiring'; ?>
                        </a>

                    </div>
                </div>

                <!-- Skill Exchange -->
                <div class="col-md-6">
                    <div class="home-feature-card">

                        <i class="bi bi-arrow-left-right"></i>

                        <h3>Skill Exchange</h3>

                        <p>
                            Teach what you know and learn what you want from others in the Skillify community.
                        </p>

                        <a href="skillexchange.php" class="btn btn-primary-custom">
                            Explore Skills
                        </a>

                    </div>
                </div>

            </div>
        </div>
    </section>

    <section class="why-skillify">
        <div class="container text-center">

            <h2 class="section-title">Why Choose Skillify?</h2>

            <div class="row g-4">

                <div class="col-md-4">
                    <div class="why-card">
                        <i class="bi bi-lightning-charge"></i>
                        <h4>Fast Opportunities</h4>
                        <p>Quickly find jobs or candidates without complicated processes.</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="why-card">
                        <i class="bi bi-people"></i>
                        <h4>Community Learning</h4>
                        <p>Connect with others to exchange skills and grow together.</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="why-card">
                        <i class="bi bi-shield-check"></i>
                        <h4>Secure Platform</h4>
                        <p>Safe and reliable environment for professional connections.</p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section class="how-section">
        <div class="container text-center">

            <h2 class="section-title">How Skillify Works</h2>

            <div class="row g-4">

                <div class="col-md-4">
                    <div class="step-card">
                        <span>1</span>
                        <h4>Create Profile</h4>
                        <p>Build your profile and showcase your skills.</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="step-card">
                        <span>2</span>
                        <h4>Connect</h4>
                        <p>Find jobs, hire talent, or exchange skills.</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="step-card">
                        <span>3</span>
                        <h4>Grow</h4>
                        <p>Improve your career and knowledge with Skillify.</p>
                    </div>
                </div>

            </div>
        </div>
    </section>

</main>

<?php include "./includes/footer.php"; ?>
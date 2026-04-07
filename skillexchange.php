<?php include "./includes/header.php"; ?>

<main>
    <section class="skill-exchange-section">
        <div class="container">
            <!-- Page Title -->
            <div class="skill-exchange-header text-center">
                <h1>Skill Exchange</h1>
                <p>Share your skills and learn from others in the community.</p>
            </div>
            <div class="row g-4">
                <!-- Post Skill Exchange -->
                <div class="col-lg-6">
                    <div class="skill-card">
                        <img src="images/post.gif" class="skill-card-img object-fit-contain" alt="Post Skill">
                        <div class="skill-card-body">
                            <h3>Post Skill Exchange</h3>
                            <p>
                                Offer your skills and find people who want to learn from you.
                                Post a skill exchange request and connect with learners.
                            </p>
                            <a href="skillpost.php" class="btn btn-primary-custom">
                                Post Skill
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Request Skill Exchange -->
                <div class="col-lg-6">
                    <div class="skill-card">
                        <img src="images/request.gif" class="skill-card-img" alt="Request Skill">
                        <div class="skill-card-body">
                            <h3>Request / Apply for Skill</h3>
                            <p>
                                Browse available skill exchange posts and apply to learn
                                new skills from experienced members.
                            </p>
                            <a href="browseskill.php" class="btn btn-primary-custom">
                                Browse Skills
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include "./includes/footer.php"; ?>
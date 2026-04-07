<?php include "./includes/header.php"; ?>

<?php
$con = mysqli_connect("localhost", "root", "", "skillify");

$email = $_COOKIE['SEC_LOGIN'];

$user = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM users WHERE email='$email'"));
$user_id = $user['id'];

/* Applied jobs */
$appliedJobs = [];

if ($user['requested_jobs']) {
    $appliedJobs = json_decode($user['requested_jobs'], true);
}

/* Fetch jobs where no user selected */
$positionFilter = isset($_GET['position']) ? trim($_GET['position']) : "";
$skillsFilter = isset($_GET['skills']) ? trim($_GET['skills']) : "";

$where = "selected_user_id IS NULL";

if ($positionFilter != "") {
    $positionFilter = mysqli_real_escape_string($con, $positionFilter);
    $where .= " AND position LIKE '%$positionFilter%'";
}

if ($skillsFilter != "") {
    $skillsFilter = mysqli_real_escape_string($con, $skillsFilter);
    $where .= " AND skills LIKE '%$skillsFilter%'";
}

$jobs = mysqli_query($con, "
SELECT *
FROM jobs
WHERE $where
ORDER BY created_at DESC
");

if (isset($_POST['apply_job'])) {

    /* PROFILE VALIDATION */

    if (empty($user['firstname']) || empty($user['lastname']) || empty($user['mobile']) || empty($user['email']) || empty($user['address']) || empty($user['resume'])) {
        echo '
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>

        Swal.fire({
            icon: "warning",
            title: "Profile Incomplete",
            text: "Please complete your profile before applying for a job.",
            background: "#1a1a1a",
            color: "#fff",
            confirmButtonColor: "#b7ee73"
        }).then(() => {
            window.location = "profile.php";
        });

        </script>
        ';

        exit();
    }

    $job_id = $_POST['job_id'];

    /* ------- JOB TABLE UPDATE ------- */

    $job = mysqli_fetch_assoc(mysqli_query($con, "SELECT requested_user_ids FROM jobs WHERE id='$job_id'"));

    $requested = [];

    if ($job['requested_user_ids']) {
        $requested = json_decode($job['requested_user_ids'], true);
    }

    if (!in_array((int) $user_id, $requested)) {
        $requested[] = (int) $user_id;
    }

    $requested_json = json_encode($requested);

    mysqli_query($con, "UPDATE jobs 
        SET requested_user_ids='$requested_json'
        WHERE id='$job_id'
    ");

    /* ------- USER TABLE UPDATE ------- */

    $user_jobs = [];

    if ($user['requested_jobs']) {
        $user_jobs = json_decode($user['requested_jobs'], true);
    }

    if (!in_array((int) $job_id, $user_jobs)) {
        $user_jobs[] = (int) $job_id;
    }

    $user_jobs_json = json_encode($user_jobs);

    mysqli_query($con, "UPDATE users 
        SET requested_jobs='$user_jobs_json'
        WHERE id='$user_id'
    ");

    header("Location: profile.php");
    exit();
}
?>

<main>

    <section class="container skill-exchange-section">

        <div class="skill-exchange-header text-center">
            <h1>Available Jobs</h1>
            <p>Explore opportunities posted by recruiters.</p>
        </div>

        <form method="GET" class="row g-3 mb-4 needs-validation" novalidate>

            <div class="col-md-4">

                <input type="text" name="position" class="form-control" placeholder="Search by position"
                    value="<?php echo $positionFilter; ?>" required>

                <div class="invalid-feedback">
                    Please enter a job position.
                </div>

            </div>

            <div class="col-md-4">

                <input type="text" name="skills" class="form-control" placeholder="Search by skills (React, PHP, etc)"
                    value="<?php echo $skillsFilter; ?>" required>

                <div class="invalid-feedback">
                    Please enter a skill.
                </div>

            </div>

            <div class="col-md-2">

                <button class="btn btn-primary-custom w-100">

                    Search

                </button>

            </div>

            <div class="col-md-2">
                <a href="jobs.php" class="btn btn-outline-secondary w-100">
                    Reset
                </a>
            </div>

        </form>

        <div class="row g-4">

            <?php
            $jobFound = false;
            while ($job = mysqli_fetch_assoc($jobs)) {

                $job_id = $job['id'];

                /* Hide already applied jobs */
                if (in_array($job_id, $appliedJobs)) {
                    continue;
                }

                $jobFound = true;
                ?>

                <div class="col-lg-4 col-md-6">

                    <div class="skill-card">

                        <div class="skill-card-body">

                            <h3><?php echo $job['position']; ?></h3>

                            <p>
                                <strong>Skills:</strong>
                                <?php echo $job['skills']; ?>
                            </p>

                            <p>
                                <strong>Experience:</strong>
                                <?php echo $job['experience']; ?> Years
                            </p>

                            <p>
                                <strong>Type:</strong>
                                <?php echo $job['job_type']; ?>
                            </p>

                            <p>
                                <strong>Location:</strong>
                                <?php echo $job['location']; ?>
                            </p>

                            <p>
                                <strong>Office Time:</strong>
                                <?php echo $job['office_start'] . " - " . $job['office_end']; ?>
                            </p>

                            <form method="POST">
                                <input type="hidden" name="job_id" value="<?php echo $job_id; ?>">

                                <button class="btn btn-primary-custom w-100" name="apply_job">
                                    Apply Job
                                </button>

                            </form>

                        </div>
                    </div>
                </div>

            <?php } ?>

            <?php if (!$jobFound) { ?>

                <div class="col-12 text-center">

                    <img src="images/nodata.gif" style="width:20rem;margin-bottom:1rem;">

                    <h4 style="color:#999;">No Jobs Available</h4>

                </div>

            <?php } ?>

        </div>
    </section>

</main>

<?php include "./includes/footer.php"; ?>
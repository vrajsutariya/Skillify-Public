<?php
include "./includes/header.php";

if (isset($_POST['post_job'])) {

    $position = mysqli_real_escape_string($con, $_POST['position']);
    $skills = mysqli_real_escape_string($con, $_POST['skills']);
    $experience = $_POST['experience'];
    $jobtype = $_POST['jobtype'];
    $office_start = $_POST['office_start'];
    $office_end = $_POST['office_end'];
    $location = mysqli_real_escape_string($con, $_POST['location']);

    $userid = $user['id'];

    $query = "
    INSERT INTO jobs
    (user_id,position,skills,experience,job_type,office_start,office_end,location)
    VALUES
    ('$userid','$position','$skills','$experience','$jobtype','$office_start','$office_end','$location')
    ";

    mysqli_query($con, $query);

    header("Location: profile.php");
    exit();
}
?>

<main>

    <section class="container hire-section mt-5">

        <div class="row justify-content-center">
            <div class="col-lg-8">

                <div class="card-custom p-4">

                    <h2 class="mb-4 text-center" style="color:#b7ee73;">Post a Job</h2>

                    <form class="needs-validation" method="POST" novalidate>

                        <!-- Position -->
                        <div class="mb-3">
                            <label class="form-label">Position</label>
                            <input type="text" class="form-control" name="position" placeholder="Laravel Developer"
                                required>
                            <div class="invalid-feedback">
                                Please enter job position.
                            </div>
                        </div>

                        <!-- Skills -->
                        <div class="mb-3">
                            <label class="form-label">Required Skills</label>
                            <textarea class="form-control" name="skills" rows="3" placeholder="PHP, MySQL, JavaScript"
                                required></textarea>
                            <div class="invalid-feedback">
                                Please enter required skills.
                            </div>
                        </div>

                        <div class="row">

                            <!-- Experience -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Experience (Years)</label>
                                <input type="number" class="form-control" step="0.1" name="experience" placeholder="1.8"
                                    required>
                                <div class="invalid-feedback">
                                    Enter required experience.
                                </div>
                            </div>

                            <!-- Job Type -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Job Type</label>
                                <select class="form-select" name="jobtype" required>
                                    <option value="">Select Job Type</option>
                                    <option>Internship</option>
                                    <option>Full Time</option>
                                    <option>Part Time</option>
                                    <option>Remote</option>
                                    <option>Freelance</option>
                                </select>
                                <div class="invalid-feedback">
                                    Select job type.
                                </div>
                            </div>

                        </div>

                        <div class="row">

                            <!-- Office Start -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Office Start Time</label>
                                <input type="time" class="form-control" name="office_start" required>
                                <div class="invalid-feedback">
                                    Select start time.
                                </div>
                            </div>

                            <!-- Office End -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Office End Time</label>
                                <input type="time" class="form-control" name="office_end" required>
                                <div class="invalid-feedback">
                                    Select end time.
                                </div>
                            </div>

                        </div>

                        <!-- Location -->
                        <div class="mb-4">
                            <label class="form-label">Location (City)</label>
                            <input type="text" class="form-control" name="location" placeholder="Surat" required>
                            <div class="invalid-feedback">
                                Enter city.
                            </div>
                        </div>

                        <!-- Submit -->
                        <div class="text-center">
                            <button type="submit" name="post_job" class="btn btn-primary-custom px-4">
                                Post Job
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>

    </section>

</main>

<?php include "./includes/footer.php"; ?>
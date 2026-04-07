<?php
include "includes/header.php";

$con = mysqli_connect("localhost", "root", "", "skillify");

// Get Job ID
$id = $_GET['id'];

// Fetch job data
$job = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM jobs WHERE id = '$id'"));

// Update Job
if (isset($_POST['update_job'])) {

    $position = mysqli_real_escape_string($con, $_POST['position']);
    $skills = mysqli_real_escape_string($con, $_POST['skills']);
    $experience = mysqli_real_escape_string($con, $_POST['experience']);
    $job_type = mysqli_real_escape_string($con, $_POST['job_type']);
    $location = mysqli_real_escape_string($con, $_POST['location']);
    $office_start = mysqli_real_escape_string($con, $_POST['office_start']);
    $office_end = mysqli_real_escape_string($con, $_POST['office_end']);

    if (!empty($position) && !empty($job_type) && !empty($location)) {

        mysqli_query($con, "
            UPDATE jobs SET 
                position='$position',
                skills='$skills',
                experience='$experience',
                job_type='$job_type',
                location='$location',
                office_start='$office_start',
                office_end='$office_end'
            WHERE id='$id'
        ");

        echo "<script>window.location='jobs.php';</script>";
    }
}
?>

<main class="admin-content">
    <div class="container-fluid">

        <!-- Header -->
        <div class="mb-4">
            <h3 class="color">Edit Job</h3>
        </div>

        <!-- Form Card -->
        <div class="p-4 shadow-sm border-0 rounded-3">

            <form method="POST" class="needs-validation" novalidate>

                <div class="row g-3">

                    <!-- Position -->
                    <div class="col-md-6">
                        <label class="form-label">Job Position</label>
                        <input type="text" name="position" class="form-control" value="<?= $job['position'] ?>"
                            required>

                        <div class="invalid-feedback">
                            Please enter job position.
                        </div>
                    </div>

                    <!-- Job Type -->
                    <div class="col-md-6">
                        <label class="form-label">Job Type</label>
                        <select name="job_type" class="form-select" required>
                            <option value="">Select Type</option>
                            <option <?= $job['job_type'] == 'Internship' ? 'selected' : '' ?>>Internship</option>
                            <option <?= $job['job_type'] == 'Full Time' ? 'selected' : '' ?>>Full Time</option>
                            <option <?= $job['job_type'] == 'Part Time' ? 'selected' : '' ?>>Part Time</option>
                            <option <?= $job['job_type'] == 'Remote' ? 'selected' : '' ?>>Remote</option>
                        </select>

                        <div class="invalid-feedback">
                            Please select job type.
                        </div>
                    </div>

                    <!-- Skills -->
                    <div class="col-md-6">
                        <label class="form-label">Skills</label>
                        <input type="text" name="skills" class="form-control" value="<?= $job['skills'] ?>">

                        <div class="invalid-feedback">
                            Please enter skills.
                        </div>
                    </div>

                    <!-- Experience -->
                    <div class="col-md-6">
                        <label class="form-label">Experience (Years)</label>
                        <input type="number" step="0.1" name="experience" class="form-control"
                            value="<?= $job['experience'] ?>">

                        <div class="invalid-feedback">
                            Please enter experience.
                        </div>
                    </div>

                    <!-- Location -->
                    <div class="col-md-6">
                        <label class="form-label">Location</label>
                        <input type="text" name="location" class="form-control" value="<?= $job['location'] ?>"
                            required>

                        <div class="invalid-feedback">
                            Please enter location.
                        </div>
                    </div>

                    <!-- Office Start -->
                    <div class="col-md-3">
                        <label class="form-label">Office Start</label>
                        <input type="time" name="office_start" class="form-control" value="<?= $job['office_start'] ?>">
                    </div>

                    <!-- Office End -->
                    <div class="col-md-3">
                        <label class="form-label">Office End</label>
                        <input type="time" name="office_end" class="form-control" value="<?= $job['office_end'] ?>">
                    </div>

                    <!-- Buttons -->
                    <div class="col-12 d-flex gap-2 mt-3 flex-wrap">
                        <button type="submit" name="update_job" class="btn btn-primary-custom px-4">
                            Update Job
                        </button>

                        <a href="jobs.php" class="btn btn-outline-secondary px-4">
                            Cancel
                        </a>
                    </div>

                </div>

            </form>

        </div>

    </div>
</main>

<?php include "includes/footer.php"; ?>
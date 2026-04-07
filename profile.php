<?php include "./includes/header.php"; ?>

<?php
$con = mysqli_connect("localhost", "root", "", "skillify");

$email = $_COOKIE['SEC_LOGIN'];

$user = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM users WHERE email='$email'"));
$skillsPosted = mysqli_query($con, "SELECT se.id, learn.name AS learn_skill, know.name AS know_skill,JSON_LENGTH(se.requested_user_ids) AS request_count
FROM skill_exchange se
LEFT JOIN skills learn ON se.learning_skill_id = learn.id
LEFT JOIN skills know ON se.known_skill_id = know.id
WHERE se.user_id = " . $user['id'] . "
ORDER BY se.id DESC
");

$jobsPosted = mysqli_query($con, "SELECT j.id, j.position, j.skills, j.experience, j.job_type, j.location, JSON_LENGTH(j.requested_user_ids) AS request_count
FROM jobs j
WHERE j.user_id = " . $user['id'] . "
ORDER BY j.id DESC
");

/* ================= REQUESTED SKILL POSTS ================= */

$requestedSkills = [];

if (!empty($user['requested_posts'])) {
    $requestedIDs = json_decode($user['requested_posts'], true);
    if (count($requestedIDs) > 0) {
        $ids = implode(",", $requestedIDs);

        $requestedSkills = mysqli_query($con, "SELECT se.*, learn.name AS learn_skill, know.name AS know_skill
        FROM skill_exchange se
        LEFT JOIN skills learn ON se.learning_skill_id = learn.id
        LEFT JOIN skills know ON se.known_skill_id = know.id
        WHERE se.id IN ($ids)
        ");

        /* -------- REMOVE DELETED SKILL POSTS -------- */

        $existingIDs = [];

        while ($s = mysqli_fetch_assoc($requestedSkills)) {
            $existingIDs[] = $s['id'];
        }

        $missing = array_diff($requestedIDs, $existingIDs);

        if (count($missing) > 0) {
            $newIDs = array_diff($requestedIDs, $missing);
            $json = json_encode(array_values($newIDs));

            mysqli_query($con, "UPDATE users
            SET requested_posts='$json'
            WHERE id=" . $user['id'] . "
            ");
        }

        /* -------- RE-FETCH VALID POSTS -------- */

        if (count($existingIDs) > 0) {
            $ids = implode(",", $existingIDs);

            $requestedSkills = mysqli_query($con, "
            SELECT se.*, 
            learn.name AS learn_skill,
            know.name AS know_skill
            FROM skill_exchange se
            LEFT JOIN skills learn ON se.learning_skill_id = learn.id
            LEFT JOIN skills know ON se.known_skill_id = know.id
            WHERE se.id IN ($ids)
            ");
        }
    }
}

/* ================= REQUESTED JOBS ================= */

$requestedJobs = [];

if ($user['requested_jobs']) {
    $requestedJobIDs = json_decode($user['requested_jobs'], true);

    if (count($requestedJobIDs) > 0) {
        $ids = implode(",", $requestedJobIDs);

        $requestedJobs = mysqli_query($con, "
        SELECT *
        FROM jobs
        WHERE id IN ($ids)
        ");

        /* -------- REMOVE DELETED JOBS -------- */

        $existingIDs = [];

        while ($j = mysqli_fetch_assoc($requestedJobs)) {
            $existingIDs[] = $j['id'];
        }

        $missing = array_diff($requestedJobIDs, $existingIDs);

        if (count($missing) > 0) {
            $newIDs = array_diff($requestedJobIDs, $missing);
            $json = json_encode(array_values($newIDs));

            mysqli_query($con, "UPDATE users
            SET requested_jobs='$json'
            WHERE id=" . $user['id'] . "
            ");
        }

        /* re-fetch jobs */
        if (count($existingIDs) > 0) {
            $ids = implode(",", $existingIDs);

            $requestedJobs = mysqli_query($con, "SELECT *
            FROM jobs
            WHERE id IN ($ids)
            ");
        }
    }
}

$alertType = "";
$alertTitle = "";
$alertText = "";

/* ================= PASSWORD CHANGE ================= */

if (isset($_POST['change_password'])) {

    $user_id = $user['id'];

    $old = $_POST['old_password'];
    $new = $_POST['new_password'];
    $renew = $_POST['renew_password'];

    if (!password_verify($old, $user['password'])) {

        $alertType = "error";
        $alertTitle = "Wrong Password";
        $alertText = "Old password incorrect";

    } else if ($new != $renew) {

        $alertType = "error";
        $alertTitle = "Password Mismatch";
        $alertText = "New passwords do not match";

    } else {

        $newpass = password_hash($new, PASSWORD_DEFAULT);

        mysqli_query($con, "UPDATE users SET password='$newpass' WHERE id='$user_id'");

        $alertType = "success";
        $alertTitle = "Password Updated";
        $alertText = "Your password changed successfully";

    }
}

if (isset($_POST['update_profile'])) {

    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $mobile = $_POST['mobile'];
    $address = $_POST['address'];

    $user_id = $user['id'];

    $imageName = $user['image'];
    $resumeName = $user['resume'];

    /* ================= IMAGE UPLOAD ================= */

    if (!empty($_FILES['image']['name'])) {

        $allowedImg = ['jpg', 'jpeg', 'png', 'gif'];

        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

        if (in_array($ext, $allowedImg)) {

            $imageName = $user_id . "." . $ext;

            move_uploaded_file($_FILES['image']['tmp_name'], "images/profile/" . $imageName);

        } else {

            $alertType = "error";
            $alertTitle = "Invalid Image";
            $alertText = "Only JPG, JPEG, PNG, GIF allowed";

        }

    }

    /* ================= RESUME UPLOAD ================= */

    if ($user['role'] == "User" && !empty($_FILES['resume']['name'])) {

        $ext = strtolower(pathinfo($_FILES['resume']['name'], PATHINFO_EXTENSION));

        if ($ext == "pdf") {

            $resumeName = $user_id . ".pdf";

            move_uploaded_file($_FILES['resume']['tmp_name'], "images/resume/" . $resumeName);

        } else {

            $alertType = "error";
            $alertTitle = "Invalid Resume";
            $alertText = "Resume must be PDF";

        }

    }

    /* ================= PASSWORD CHANGE ================= */

    if (!empty($_POST['old_password']) || !empty($_POST['new_password'])) {

        $old = $_POST['old_password'];
        $new = $_POST['new_password'];
        $renew = $_POST['renew_password'];

        if (!password_verify($old, $user['password'])) {

            $alertType = "error";
            $alertTitle = "Wrong Password";
            $alertText = "Old password incorrect";

        } else if ($new != $renew) {

            $alertType = "error";
            $alertTitle = "Password Mismatch";
            $alertText = "New passwords do not match";

        } else {

            $newpass = password_hash($new, PASSWORD_DEFAULT);

            mysqli_query($con, "UPDATE users SET password='$newpass' WHERE id='$user_id'");

        }

    }

    /* ================= UPDATE PROFILE ================= */

    mysqli_query($con, "UPDATE users SET 
    firstname='$firstname',
    lastname='$lastname',
    mobile='$mobile',
    address='$address',
    image='$imageName',
    resume='$resumeName'
    WHERE id='$user_id'");

    $alertType = "success";
    $alertTitle = "Profile Updated";
    $alertText = "Your profile saved successfully";

    $user = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM users WHERE id='$user_id'"));
}

?>
<main>
    <section class="profile-section">
        <div class="container">
            <div class="row g-4">
                <!-- LEFT PROFILE CARD -->
                <div class="col-lg-4">
                    <div class="profile-card text-center">
                        <?php if ($user['image']) { ?>
                            <img src="images/profile/<?php echo $user['image']; ?>" class="profile-img">
                        <?php } else { ?>
                            <img src="images/avtar.gif" class="profile-img">
                        <?php } ?>
                        <?php if ($user['firstname'] && $user['lastname']) { ?>
                            <h3>
                                <?php echo $user['firstname'] . " " . $user['lastname']; ?>
                            </h3>
                        <?php } else { ?>
                            <p class="text-secondary">
                                <?php echo $user['email']; ?>
                            </p>
                        <?php } ?>
                        <?php if ($user['resume']) { ?>
                            <a href="images/resume/<?php echo $user['resume']; ?>" target="_blank"
                                class="btn btn-primary-custom w-100 mt-3">
                                View Resume
                            </a>
                        <?php } ?>
                        <hr>
                        <h5 style="color:#b7ee73;" class="mb-3">Change Password</h5>

                        <form method="POST" class="needs-validation" novalidate>

                            <div class="mb-2">
                                <input type="password" name="old_password" class="form-control"
                                    placeholder="Old Password" minlength="8" required>
                                <div class="invalid-feedback text-start">
                                    Please provide a valid old password.
                                </div>
                            </div>

                            <div class="mb-2">
                                <input type="password" name="new_password" class="form-control"
                                    placeholder="New Password" minlength="8" required>
                                <div class="invalid-feedback text-start">
                                    Please provide a valid new password.
                                </div>
                            </div>

                            <div class="mb-3">
                                <input type="password" name="renew_password" class="form-control"
                                    placeholder="Re-enter Password" minlength="8" required>
                                <div class="invalid-feedback text-start">
                                    Please provide a valid confirm password.
                                </div>
                            </div>

                            <button type="submit" name="change_password" class="btn btn-primary-custom w-100">
                                Update Password
                            </button>

                        </form>
                    </div>
                </div>
                <!-- RIGHT PROFILE FORM -->
                <div class="col-lg-8">
                    <div class="profile-card">
                        <h2 class="profile-title">Edit Profile</h2>
                        <form method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <input type="text" name="firstname" class="form-control" placeholder="First Name"
                                        value="<?php echo $user['firstname']; ?>" required>
                                    <div class="invalid-feedback">
                                        Please provide a valid first name.
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" name="lastname" class="form-control" placeholder="Last Name"
                                        value="<?php echo $user['lastname']; ?>" required>
                                    <div class="invalid-feedback">
                                        Please provide a valid last name.
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" name="mobile" class="form-control" placeholder="Mobile"
                                        value="<?php echo $user['mobile']; ?>" required>
                                    <div class="invalid-feedback">
                                        Please provide a valid mobile.
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <input type="email" class="form-control" value="<?php echo $user['email']; ?>"
                                        readonly>
                                </div>
                                <div class="col-12">
                                    <textarea name="address" class="form-control" rows="4" placeholder="Address"
                                        required><?php echo $user['address']; ?></textarea>
                                    <div class="invalid-feedback">
                                        Please provide a valid address.
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Profile Image</label>
                                    <input type="file" name="image" class="form-control">
                                </div>
                                <?php if ($user['role'] == "User") { ?>
                                    <div class="col-md-6">
                                        <label class="form-label">Resume (PDF)</label>
                                        <input type="file" name="resume" accept="application/pdf" class="form-control">
                                    </div>
                                <?php } ?>
                                <div class="col-12">
                                    <button type="submit" name="update_profile" class="btn btn-primary-custom w-100">
                                        Save Profile
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php if (mysqli_num_rows($jobsPosted) > 0) { ?>
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="profile-card">
                            <h2 class="profile-title mb-4">Your Job Posts</h2>
                            <div class="row g-4">
                                <?php while ($job = mysqli_fetch_assoc($jobsPosted)) { ?>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="skill-card">
                                            <div class="skill-card-body">
                                                <h3>
                                                    <?php echo $job['position']; ?>
                                                </h3>
                                                <p>
                                                    <?php echo $job['job_type']; ?> •
                                                    <?php echo $job['location']; ?><br>
                                                    Experience: <strong>
                                                        <?php echo $job['experience']; ?> yrs
                                                    </strong>
                                                </p>
                                                <p style="font-size:0.85rem;">
                                                    <?php echo substr($job['skills'], 0, 60); ?>...
                                                </p>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="request-count">
                                                        <?php echo ($job['request_count'] ? $job['request_count'] : 0); ?>
                                                        Applicants
                                                    </span>
                                                    <a href="jobpostdetail.php?id=<?php echo $job['id']; ?>"
                                                        class="btn btn-primary-custom btn-sm">
                                                        View Detail
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <?php if (!empty($requestedJobs) && mysqli_num_rows($requestedJobs) > 0) { ?>
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="profile-card">
                            <h2 class="profile-title mb-4">Applied Jobs</h2>
                            <div class="row g-4">
                                <?php while ($job = mysqli_fetch_assoc($requestedJobs)) {
                                    $btnClass = "btn-outline-secondary";
                                    $btnText = "Requested";
                                    if ($job['selected_user_id']) {
                                        if ($job['selected_user_id'] == $user['id']) {
                                            $btnClass = "btn-primary-custom";
                                            $btnText = "Accepted";
                                        } else {
                                            $btnClass = "btn-outline-danger";
                                            $btnText = "Rejected";
                                        }
                                    }
                                    ?>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="skill-card">
                                            <div class="skill-card-body">
                                                <h3>
                                                    <?php echo $job['position']; ?>
                                                </h3>
                                                <p>
                                                    <?php echo $job['job_type']; ?> •
                                                    <?php echo $job['location']; ?><br>
                                                    Experience: <strong>
                                                        <?php echo $job['experience']; ?> yrs
                                                    </strong>
                                                </p>
                                                <p style="font-size:0.85rem;">
                                                    <?php echo substr($job['skills'], 0, 60); ?>...
                                                </p>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="request-count">
                                                        <?php echo date("d M Y", strtotime($job['created_at'])); ?>
                                                    </span>
                                                    <button type="button" class="btn <?php echo $btnClass; ?> btn-sm">
                                                        <?php echo $btnText; ?>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <!-- SKILL EXCHANGE POSTS -->
            <?php if (mysqli_num_rows($skillsPosted) > 0) { ?>
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="profile-card">
                            <h2 class="profile-title mb-4">Your Skill Exchange Posts</h2>
                            <div class="row g-4">
                                <?php while ($row = mysqli_fetch_assoc($skillsPosted)) { ?>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="skill-card">
                                            <div class="skill-card-body">
                                                <h3><?php echo $row['learn_skill']; ?></h3>
                                                <p>
                                                    You can teach <strong><?php echo $row['know_skill']; ?></strong>
                                                </p>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="request-count">
                                                        <?php echo ($row['request_count'] ? $row['request_count'] : 0); ?>
                                                        Requests
                                                    </span>
                                                    <a href="skillpostdetail.php?id=<?php echo $row['id']; ?>"
                                                        class="btn btn-primary-custom btn-sm">
                                                        View Detail
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <?php if (!empty($requestedSkills) && mysqli_num_rows($requestedSkills) > 0) { ?>
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="profile-card">
                            <h2 class="profile-title mb-4">Requested Skill Exchanges</h2>
                            <div class="row g-4">
                                <?php while ($row = mysqli_fetch_assoc($requestedSkills)) {
                                    /* BUTTON STATUS */
                                    $btnClass = "btn-outline-secondary";
                                    $btnText = "Requested";
                                    if ($row['selected_user_id']) {
                                        if ($row['selected_user_id'] == $user['id']) {
                                            $btnClass = "btn-primary-custom";
                                            $btnText = "Accepted";
                                        } else {
                                            $btnClass = "btn-outline-danger";
                                            $btnText = "Rejected";
                                        }
                                    }
                                    ?>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="skill-card">
                                            <div class="skill-card-body">
                                                <h3>
                                                    <?php echo $row['know_skill']; ?>
                                                </h3>
                                                <p>
                                                    You teach <strong>
                                                        <?php echo $row['learn_skill']; ?>
                                                    </strong>
                                                </p>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="request-count">
                                                        <?php echo ($row['status'] == "completed") ? "Completed" : "Open"; ?>
                                                    </span>
                                                    <button type="button" class="btn <?php echo $btnClass; ?> btn-sm">
                                                        <?php echo $btnText; ?>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </section>
</main>

<?php if ($alertType != "") { ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>

        Swal.fire({
            icon: "<?php echo $alertType; ?>",
            title: "<?php echo $alertTitle; ?>",
            text: "<?php echo $alertText; ?>",
            background: "#1a1a1a",
            color: "#fff",
            confirmButtonColor: "#b7ee73"
        })
    </script>
<?php } ?>

<?php include "./includes/footer.php"; ?>
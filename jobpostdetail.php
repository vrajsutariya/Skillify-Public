<?php include "./includes/header.php"; ?>

<?php
$con = mysqli_connect("localhost", "root", "", "skillify");

$post_id = $_GET['id'];
$email = $_COOKIE['SEC_LOGIN'];

$user = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM users WHERE email='$email'"));

$post = mysqli_fetch_assoc(mysqli_query($con, "
SELECT *
FROM jobs
WHERE id='$post_id'
"));

/* SECURITY CHECK */

if ($user['id'] != $post['user_id']) {
    header("Location: profile.php");
    exit();
}

/* GET REQUESTED USERS */

$requestedUsers = [];

if ($post['requested_user_ids']) {
    $requestedUsers = json_decode($post['requested_user_ids'], true);
}
?>

<?php

/* ACCEPT USER */

if (isset($_POST['accept_user'])) {

    $selected_user = $_POST['user_id'];

    mysqli_query($con, "
    UPDATE jobs 
    SET selected_user_id='$selected_user'
    WHERE id='$post_id'
    ");

    header("Location: " . $_SERVER['REQUEST_URI']);
    exit();
}

/* DELETE POST */

if (isset($_POST['delete_post'])) {

    mysqli_query($con, "DELETE FROM jobs WHERE id='$post_id'");

    header("Location: profile.php");
    exit();
}

?>

<main>
    <section class="skill-exchange-section">

        <div class="container">

            <div class="profile-card">

                <h2 class="profile-title mb-4">Job Post Detail</h2>

                <p><strong>Position:</strong> <?php echo $post['position']; ?></p>

                <p><strong>Skills:</strong> <?php echo $post['skills']; ?></p>

                <p><strong>Experience:</strong> <?php echo $post['experience']; ?> Years</p>

                <p><strong>Job Type:</strong> <?php echo $post['job_type']; ?></p>

                <p><strong>Location:</strong> <?php echo $post['location']; ?></p>

                <p>
                    <strong>Office Time:</strong>
                    <?php echo $post['office_start']; ?>
                    -
                    <?php echo $post['office_end']; ?>
                </p>

                <hr class="my-4">

                <h4 class="mb-3">Applicants</h4>

                <?php

                if (empty($requestedUsers)) {

                    echo '<p class="text-secondary">No applicants yet.</p>';

                } else {

                    foreach ($requestedUsers as $uid) {

                        $applicant = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM users WHERE id='$uid'"));
                        ?>

                        <div class="request-row d-flex align-items-center justify-content-between mb-3">

                            <!-- LEFT SIDE USER DETAIL -->

                            <div class="d-flex align-items-center">

                                <?php if ($applicant['image']) { ?>
                                    <img src="images/profile/<?php echo $applicant['image']; ?>" class="request-avatar">
                                <?php } else { ?>
                                    <img src="images/avtar.gif" class="request-avatar">
                                <?php } ?>

                                <div class="ms-3">

                                    <strong>
                                        <?php
                                        echo $applicant['firstname']
                                            ? $applicant['firstname'] . " " . $applicant['lastname']
                                            : "User";
                                        ?>
                                    </strong>

                                    <br>

                                    <span class="text-secondary">
                                        <?php echo $applicant['email']; ?>
                                    </span>

                                    <br>

                                    <span class="text-secondary">
                                        <?php echo $applicant['mobile']; ?>
                                    </span>

                                    <br>

                                    <span class="text-secondary">
                                        <?php echo $applicant['address']; ?>
                                    </span>

                                </div>

                            </div>


                            <!-- RIGHT SIDE BUTTONS -->

                            <div class="d-flex align-items-center gap-2">

                                <!-- RESUME BUTTON -->

                                <?php if ($applicant['resume']) { ?>

                                    <a href="images/resume/<?php echo $applicant['resume']; ?>" target="_blank"
                                        class="btn btn-outline-secondary btn-sm">
                                        Resume
                                    </a>

                                <?php } ?>


                                <!-- ACCEPT BUTTON LOGIC -->

                                <?php

                                if ($post['selected_user_id']) {

                                    if ($post['selected_user_id'] == $uid) {

                                        echo '<button type="button" class="btn btn-primary-custom btn-sm">Selected</button>';

                                    } else {

                                        echo '<button type="button" class="btn btn-outline-danger btn-sm">Rejected</button>';

                                    }

                                } else {
                                    ?>

                                    <form method="POST">

                                        <input type="hidden" name="user_id" value="<?php echo $uid; ?>">

                                        <button type="submit" name="accept_user" class="btn btn-primary-custom btn-sm">
                                            Accept
                                        </button>

                                    </form>

                                <?php } ?>

                            </div>

                        </div>

                        <?php
                    }
                }
                ?>
                <hr class="my-4">

                <div class="text-end">

                    <?php if (!$post['selected_user_id']) { ?>

                        <form method="POST">

                            <button name="delete_post" class="btn btn-outline-danger">
                                Delete Job Post
                            </button>

                        </form>

                    <?php } else { ?>

                        <button type="button" class="btn btn-primary-custom">
                            Candidate Selected
                        </button>

                    <?php } ?>

                </div>

            </div>

        </div>

    </section>
</main>

<?php include "./includes/footer.php"; ?>
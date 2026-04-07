<?php include "./includes/header.php"; ?>

<?php
$con = mysqli_connect("localhost", "root", "", "skillify");
$post_id = $_GET['id'];
$email = $_COOKIE['SEC_LOGIN'];

$user = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM users WHERE email='$email'"));
$post = mysqli_fetch_assoc(mysqli_query($con, "
SELECT se.*, learn.name AS learn_skill, know.name AS know_skill
FROM skill_exchange se
LEFT JOIN skills learn ON se.learning_skill_id = learn.id
LEFT JOIN skills know ON se.known_skill_id = know.id
WHERE se.id='$post_id'
"));

if ($user['id'] != $post['user_id']) {
    header("Location: profile.php");
    exit();
}

$requestedUsers = [];
if ($post['requested_user_ids']) {
    $requestedUsers = json_decode($post['requested_user_ids'], true);
}
?>

<?php
if (isset($_POST['accept_user'])) {
    $selected_user = $_POST['user_id'];

    mysqli_query($con, "UPDATE skill_exchange SET selected_user_id='$selected_user', status='completed' WHERE id='$post_id'");
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit();
}

if (isset($_POST['delete_post'])) {
    mysqli_query($con, "DELETE FROM skill_exchange WHERE id='$post_id'");
    header("Location: profile.php");
    exit();
}
?>

<main>
    <section class="skill-exchange-section">
        <div class="container">
            <div class="profile-card">
                <h2 class="profile-title mb-4">Skill Exchange Detail</h2>
                <p><strong>Learn Skill:</strong> <?php echo $post['learn_skill']; ?></p>
                <p><strong>Teach Skill:</strong> <?php echo $post['know_skill']; ?></p>
                <hr class="my-4">
                <h4 class="mb-3">Requested Users</h4>
                <?php if (empty($requestedUsers)) { ?>
                    <p class="text-secondary">No requests yet.</p>
                <?php } else {
                    foreach ($requestedUsers as $uid) {
                        $user = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM users WHERE id='$uid'"));
                        ?>
                        <div class="request-row d-flex align-items-center justify-content-between mb-3">
                            <div class="d-flex align-items-center w-100">
                                <?php if ($user['image']) { ?>
                                    <img src="images/profile/<?php echo $user['image']; ?>" class="request-avatar">
                                <?php } else { ?>
                                    <img src="images/avtar.gif" class="request-avatar">
                                <?php } ?>
                                <div class="ms-3">
                                    <strong>
                                        <?php
                                        echo $user['firstname'] ? $user['firstname'] . " " . $user['lastname'] : "User";
                                        ?>
                                    </strong>
                                    <br>
                                    <span class="text-secondary">
                                        <?php echo $user['email']; ?>
                                    </span>
                                </div>
                            </div>
                            <div>
                                <?php
                                if ($post['selected_user_id']) {
                                    if ($post['selected_user_id'] == $uid) {
                                        echo '<button class="btn btn-primary-custom btn-sm" disabled>Accepted</button>';
                                    } else {
                                        echo '<button class="btn btn-outline-danger btn-sm" disabled>Rejected</button>';

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
                    <?php }
                } ?>
                <hr class="my-4">

                <div class="text-end">
                    <?php if (!$post['selected_user_id']) { ?>
                        <form method="POST">
                            <button name="delete_post" class="btn btn-outline-danger">
                                Delete Post
                            </button>
                        </form>
                    <?php } else { ?>
                        <form method="POST">
                            <button type="button" name="complete_post" class="btn btn-primary-custom">
                                Completed
                            </button>
                        </form>
                    <?php } ?>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include "./includes/footer.php"; ?>
<?php include "./includes/header.php"; ?>

<?php

$con = mysqli_connect("localhost", "root", "", "skillify");

$email = $_COOKIE['SEC_LOGIN'];

$user = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM users WHERE email='$email'"));

$user_id = $user['id'];


/* ================= USER REQUESTED POSTS ================= */

$requestedPosts = [];

if ($user['requested_posts']) {
    $requestedPosts = json_decode($user['requested_posts'], true);
}


/* ================= REQUEST BUTTON ================= */

if (isset($_POST['request_post'])) {

    $post_id = (int) $_POST['post_id'];

    $post = mysqli_fetch_assoc(mysqli_query($con, "SELECT requested_user_ids FROM skill_exchange WHERE id=$post_id"));

    $requestedUsers = [];

    if ($post['requested_user_ids']) {
        $requestedUsers = json_decode($post['requested_user_ids'], true);
    }

    $requestedUsers[] = (int) $user_id;

    $jsonUsers = json_encode($requestedUsers);

    mysqli_query($con, "UPDATE skill_exchange SET requested_user_ids='$jsonUsers' WHERE id=$post_id");


    /* ADD POST ID IN USER TABLE */

    $requestedPosts[] = (int) $post_id;

    $jsonPosts = json_encode($requestedPosts);

    mysqli_query($con, "UPDATE users SET requested_posts='$jsonPosts' WHERE id=$user_id");


    header("Location: profile.php");
    exit();

}


/* ================= FILTER ================= */

$learnFilter = "";
$teachFilter = "";

if (isset($_GET['learn'])) {

    $learnFilter = (int) $_GET['learn'];

}

if (isset($_GET['teach'])) {

    $teachFilter = (int) $_GET['teach'];

}


/* ================= FETCH POSTS ================= */

$query = "
SELECT se.id,
learn.name AS learn_skill,
know.name AS know_skill,
JSON_LENGTH(se.requested_user_ids) AS request_count
FROM skill_exchange se
LEFT JOIN skills learn ON se.learning_skill_id = learn.id
LEFT JOIN skills know ON se.known_skill_id = know.id
WHERE se.status='open'
AND se.user_id != $user_id
";


if ($learnFilter) {
    $query .= " AND se.known_skill_id=$learnFilter";
}

if ($teachFilter) {
    $query .= " AND se.learning_skill_id=$teachFilter";
}

$query .= " ORDER BY se.id DESC";

$result = mysqli_query($con, $query);


/* ================= SKILLS LIST ================= */

$skills = mysqli_query($con, "SELECT * FROM skills");

?>

<main>

    <section class="profile-section">

        <div class="container">


            <h2 class="profile-title mb-4">Browse Skill Exchange</h2>


            <!-- FILTER -->

            <form method="GET" class="row g-3 mb-4 needs-validation" novalidate>

                <div class="col-md-4">

                    <select name="learn" id="learnSkill" class="form-control" required>

                        <option value="">Skill you want to learn</option>

                        <?php

                        mysqli_data_seek($skills, 0);

                        while ($skill = mysqli_fetch_assoc($skills)) {

                            ?>

                            <option value="<?php echo $skill['id']; ?>" <?php if ($learnFilter == $skill['id'])
                                   echo "selected"; ?>>

                                <?php echo $skill['name']; ?>

                            </option>

                        <?php } ?>

                    </select>
                    <div class="invalid-feedback">
                        Please select a valid skill.
                    </div>

                </div>


                <div class="col-md-4">

                    <select name="teach" id="teachSkill" class="form-control" disabled required>

                        <option value="">Skill you can teach</option>

                        <?php

                        mysqli_data_seek($skills, 0);

                        while ($skill = mysqli_fetch_assoc($skills)) {

                            ?>

                            <option value="<?php echo $skill['id']; ?>" <?php if ($teachFilter == $skill['id'])
                                   echo "selected"; ?>>

                                <?php echo $skill['name']; ?>

                            </option>

                        <?php } ?>

                    </select>
                    <div class="invalid-feedback">
                        Please select a valid skill.
                    </div>

                </div>


                <div class="col-md-2">

                    <button class="btn btn-primary-custom w-100">

                        Search

                    </button>

                </div>

                <div class="col-md-2">
                    <a href="browseskill.php" class="btn btn-outline-secondary w-100">
                        Reset
                    </a>
                </div>

            </form>



            <div class="row g-4">

                <?php

                $count = 0;

                while ($row = mysqli_fetch_assoc($result)) {

                    if (in_array($row['id'], $requestedPosts)) {
                        continue;
                    }

                    $count++;

                    ?>

                    <div class="col-lg-4 col-md-6">

                        <div class="skill-card">

                            <div class="skill-card-body">

                                <h3><?php echo $row['know_skill']; ?></h3>

                                <p>
                                    You should teach <strong><?php echo $row['learn_skill']; ?></strong>
                                </p>

                                <div class="d-flex justify-content-between align-items-center">

                                    <span class="request-count">

                                        <?php echo ($row['request_count'] ? $row['request_count'] : 0); ?>

                                        Requests

                                    </span>

                                    <form method="POST">

                                        <input type="hidden" name="post_id" value="<?php echo $row['id']; ?>">

                                        <button class="btn btn-primary-custom btn-sm" name="request_post">

                                            Request

                                        </button>

                                    </form>

                                </div>

                            </div>

                        </div>

                    </div>

                <?php } ?>


                <?php if ($count == 0) { ?>

                    <div class="col-12 text-center">

                        <img src="images/nodata.gif" style="width:20rem;margin-bottom:1rem;">

                        <h4 style="color:#999;">No Skill Exchange Found</h4>

                    </div>

                <?php } ?>


            </div>

        </div>

    </section>

</main>

<script>
    let learn = document.getElementById("learnSkill")
    let teach = document.getElementById("teachSkill")

    learn.addEventListener("change", function () {

        let selectedSkill = this.value

        teach.value = ""

        if (selectedSkill != "") {
            teach.disabled = false
        } else {
            teach.disabled = true
        }

        for (let i = 0; i < teach.options.length; i++) {

            if (teach.options[i].value == selectedSkill) {
                teach.options[i].style.display = "none"
            } else {
                teach.options[i].style.display = "block"
            }

        }

    })
</script>

<?php include "./includes/footer.php"; ?>
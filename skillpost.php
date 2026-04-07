<?php include "./includes/header.php"; ?>
<?php
$con = mysqli_connect("localhost", "root", "", "skillify");

$email = $_COOKIE['SEC_LOGIN'];

$user = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM users WHERE email='$email'"));

$skills = mysqli_query($con, "SELECT * FROM skills");

$alertType = "";
$alertTitle = "";
$alertText = "";


if (isset($_POST['post_skill'])) {

    $user_id = $user['id'];

    $learning_skill = $_POST['learning_skill'];
    $known_skill = $_POST['known_skill'];

    if ($learning_skill == $known_skill) {
        $alertType = "error";
        $alertTitle = "Invalid Selection";
        $alertText = "Learning skill and Known skill cannot be same";
    } else {
        mysqli_query($con, "INSERT INTO skill_exchange 
           (user_id,learning_skill_id,known_skill_id)
           VALUES
           ('$user_id','$learning_skill','$known_skill')");

        $alertType = "success";
        $alertTitle = "Skill Posted";
        $alertText = "Your skill exchange post created successfully";
    }
}

?>
<main>
    <section class="profile-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="profile-card">
                        <h2 class="profile-title">Post Skill Exchange</h2>
                        <form method="POST" class="needs-validation" novalidate>
                            <!-- WANT TO LEARN -->
                            <div class="mb-3">
                                <label class="form-label">Skill You Want To Learn</label>
                                <select name="learning_skill" id="learnSkill" class="form-control" required>
                                    <option value="">Select Skill</option>
                                    <?php
                                    mysqli_data_seek($skills, 0);
                                    while ($row = mysqli_fetch_assoc($skills)) {
                                        ?>
                                        <option value="<?php echo $row['id']; ?>">
                                            <?php echo $row['name']; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                                <div class="invalid-feedback">
                                    Please select skill you want to learn
                                </div>
                            </div>
                            <!-- KNOWN SKILL -->
                            <div class="mb-3">
                                <label class="form-label">Skill You Can Teach</label>
                                <select name="known_skill" id="knownSkill" class="form-control" disabled>
                                    <option value="">Select Skill</option>
                                    <?php
                                    mysqli_data_seek($skills, 0);
                                    while ($row = mysqli_fetch_assoc($skills)) {
                                        ?>
                                        <option value="<?php echo $row['id']; ?>">
                                            <?php echo $row['name']; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                                <div class="invalid-feedback">
                                    Please select your known skill
                                </div>
                            </div>
                            <button type="submit" name="post_skill" class="btn btn-primary-custom w-100">
                                Post Skill Exchange
                            </button>
                        </form>
                    </div>
                </div>
            </div>
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
        }).then((result) => {
            if (result.isConfirmed && "<?php echo $alertType; ?>" === "success") {
                window.location.href = "profile.php";
            }
        });
    </script>
<?php } ?>

<script>
    const learnSkill = document.getElementById("learnSkill");
    const knownSkill = document.getElementById("knownSkill");

    learnSkill.addEventListener("change", function () {

        const selectedLearn = this.value;

        // reset knownSkill value
        knownSkill.value = "";

        if (selectedLearn !== "") {
            knownSkill.disabled = false;
            knownSkill.required = true;
        } else {
            knownSkill.disabled = true;
            knownSkill.required = false;
        }

        const options = knownSkill.options;

        for (let i = 0; i < options.length; i++) {

            if (options[i].value === selectedLearn) {
                options[i].style.display = "none";
            } else {
                options[i].style.display = "block";
            }
        }
    });
</script>

<?php include "./includes/footer.php"; ?>
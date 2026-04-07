<?php
include "includes/header.php";

$con = mysqli_connect("localhost", "root", "", "skillify");

// Get ID
$id = $_GET['id'];

// Fetch single record
$data = mysqli_fetch_assoc(mysqli_query($con, "
    SELECT se.*, 
           u.firstname, u.lastname, u.image
    FROM skill_exchange se
    JOIN users u ON se.user_id = u.id
    WHERE se.id = $id
"));

// Fetch skills for dropdown
$skills = mysqli_query($con, "SELECT * FROM skills ORDER BY name ASC");

// Update Logic
if (isset($_POST['update'])) {
    $learning = $_POST['learning_skill'];
    $known = $_POST['known_skill'];
    $status = $_POST['status'];

    mysqli_query($con, "
        UPDATE skill_exchange 
        SET learning_skill_id='$learning',
            known_skill_id='$known',
            status='$status'
        WHERE id=$id
    ");

    header("Location: skillexchange.php");
}
?>

<main class="admin-content">
    <div class="container-fluid">

        <h3 class="mb-4 color">Edit Skill Exchange</h3>

        <div class="p-4 shadow-sm border-0">

            <!-- User Info -->
            <div class="d-flex align-items-center gap-3 mb-4">
                <img src="<?= !empty($data['image']) ? "../images/profile/" . $data['image'] : "../images/avtar.gif" ?>"
                    class="user-img">

                <div>
                    <h5 class="mb-0">
                        <?= $data['firstname'] . " " . $data['lastname'] ?>
                    </h5>
                    <small>Posted Skill Exchange</small>
                </div>
            </div>

            <!-- Form -->
            <form method="POST" class="needs-validation" novalidate>

                <div class="row g-3">

                    <!-- Learning Skill -->
                    <div class="col-md-6">
                        <label class="form-label">Learning Skill</label>
                        <select name="learning_skill" class="form-select" required>
                            <option value="">Select Skill</option>

                            <?php mysqli_data_seek($skills, 0);
                            while ($skill = mysqli_fetch_assoc($skills)) { ?>
                                <option value="<?= $skill['id'] ?>" <?= $skill['id'] == $data['learning_skill_id'] ? 'selected' : '' ?>>
                                    <?= $skill['name'] ?>
                                </option>
                            <?php } ?>
                        </select>

                        <div class="invalid-feedback">
                            Please select learning skill
                        </div>
                    </div>

                    <!-- Known Skill -->
                    <div class="col-md-6">
                        <label class="form-label">Teaching Skill</label>
                        <select name="known_skill" class="form-select" required>
                            <option value="">Select Skill</option>

                            <?php mysqli_data_seek($skills, 0);
                            while ($skill = mysqli_fetch_assoc($skills)) { ?>
                                <option value="<?= $skill['id'] ?>" <?= $skill['id'] == $data['known_skill_id'] ? 'selected' : '' ?>>
                                    <?= $skill['name'] ?>
                                </option>
                            <?php } ?>
                        </select>

                        <div class="invalid-feedback">
                            Please select teaching skill
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="col-md-6">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="">Select Status</option>
                            <option value="open" <?= $data['status'] == 'open' ? 'selected' : '' ?>>Open</option>
                            <option value="completed" <?= $data['status'] == 'completed' ? 'selected' : '' ?>>Completed
                            </option>
                        </select>

                        <div class="invalid-feedback">
                            Please select status
                        </div>
                    </div>

                </div>

                <!-- Buttons -->
                <div class="mt-4 d-flex gap-2">
                    <button type="submit" name="update" class="btn btn-primary-custom">
                        Update
                    </button>

                    <a href="skillexchange.php" class="btn btn-secondary">
                        Cancel
                    </a>
                </div>

            </form>

        </div>

    </div>
</main>

<script>
    const learningSelect = document.querySelector('[name="learning_skill"]');
    const knownSelect = document.querySelector('[name="known_skill"]');

    function filterKnownSkills() {
        const selectedLearning = learningSelect.value;

        // Reset all options first
        Array.from(knownSelect.options).forEach(option => {
            option.hidden = false;
        });

        // Hide selected learning skill in known dropdown
        if (selectedLearning) {
            Array.from(knownSelect.options).forEach(option => {
                if (option.value === selectedLearning) {
                    option.hidden = true;

                    // If already selected, reset it
                    if (knownSelect.value === selectedLearning) {
                        knownSelect.value = "";
                    }
                }
            });
        }
    }

    // Run on change
    learningSelect.addEventListener('change', filterKnownSkills);

    // Run on page load (important for edit page)
    window.addEventListener('load', filterKnownSkills);
</script>

<?php include "includes/footer.php"; ?>
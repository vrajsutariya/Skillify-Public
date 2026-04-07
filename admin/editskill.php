<?php
include "includes/header.php";

$con = mysqli_connect("localhost", "root", "", "skillify");

// Get ID
$id = $_GET['id'];

// Fetch skill data
$skill = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM skills WHERE id='$id'"));

// Update Skill
if (isset($_POST['update_skill'])) {
    $name = mysqli_real_escape_string($con, $_POST['name']);

    if (!empty($name)) {
        mysqli_query($con, "UPDATE skills SET name='$name' WHERE id='$id'");
        echo "<script>window.location='skills.php';</script>";
    }
}
?>

<main class="admin-content">
    <div class="container-fluid">

        <!-- Page Header -->
        <div class="mb-4">
            <h3 class="color">Edit Skill</h3>
        </div>

        <!-- Form Card -->
        <div class="p-4 shadow-sm border-0">

            <form method="POST" class="needs-validation" novalidate>

                <div class="row g-3">

                    <!-- Skill Name -->
                    <div class="col-md-6">
                        <label class="form-label text-light">Skill Name</label>
                        <input type="text" name="name" class="form-control" value="<?= $skill['name'] ?>"
                            placeholder="Enter skill name" required>

                        <div class="invalid-feedback">
                            Please enter skill name.
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="col-12 d-flex gap-2 mt-3 flex-wrap">
                        <button type="submit" name="update_skill" class="btn btn-primary-custom px-4">
                            Update Skill
                        </button>

                        <a href="skills.php" class="btn btn-outline-secondary px-4">
                            Cancel
                        </a>
                    </div>

                </div>

            </form>

        </div>

    </div>
</main>

<?php include "includes/footer.php"; ?>